<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\TradePartner;
use App\Models\Currency;
use App\Models\Office;
use App\Models\User;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['billTo', 'currency', 'office', 'issuer']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('billing_address', 'like', "%{$search}%")
                  ->orWhereHas('billTo', function($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter row params
        if ($request->filled('filter_party')) {
            $party = $request->input('filter_party');
            $query->whereHas('billTo', function($q) use ($party) {
                $q->where('name', 'like', "%{$party}%");
            });
        }

        if ($request->filled('filter_file_no')) {
            $fileNo = $request->input('filter_file_no');
            $query->where(function($q) use ($fileNo) {
                $q->whereHasMorph('invoiceable', '*', function($sub) use ($fileNo) {
                    $sub->where('file_no', 'like', "%{$fileNo}%");
                });
            });
        }

        if ($request->filled('filter_inv_no')) {
            $query->where('invoice_no', 'like', '%' . $request->input('filter_inv_no') . '%');
        }

        if ($request->filled('filter_type')) {
            $query->where('type', $request->input('filter_type'));
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['invoice_no', 'invoice_date', 'due_date', 'total_amount', 'paid_amount', 'balance_amount', 'status', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $pageSize = $request->input('limit', 15);
        $invoices = $query->orderBy($sortField, $sortDir)->paginate($pageSize)->withQueryString();

        $totalInvoiceAmount = Invoice::where('status', '!=', 'VOID')->sum('total_amount');
        $totalPaidAmount = Invoice::where('status', '!=', 'VOID')->sum('paid_amount');
        $totalBalanceAmount = Invoice::where('status', '!=', 'VOID')->sum('balance_amount');

        return view('accounting.invoice-list', compact('invoices', 'totalInvoiceAmount', 'totalPaidAmount', 'totalBalanceAmount'));
    }

    public function create(Request $request)
    {
        $tradePartners = TradePartner::all();
        $currencies = Currency::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        $defaultType = $request->input('type', 'AR');
        return view('accounting.invoice-create', compact('tradePartners', 'currencies', 'offices', 'users', 'defaultType'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();
        $data['discount_pct'] = $data['discount_pct'] ?? 0;
        $data['tax_pct'] = $data['tax_pct'] ?? 0;
        $data['shipping_amount'] = $data['shipping_amount'] ?? 0;
        $data['subtotal'] = $data['subtotal'] ?? 0;
        $data['tax_total'] = $data['tax_total'] ?? 0;
        $data['paid_amount'] = $data['paid_amount'] ?? 0;
        $data['balance_amount'] = $data['total_amount'] - ($data['paid_amount'] ?? 0);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create($data);

            if ($request->filled('lines_json')) {
                $lines = json_decode($request->input('lines_json'), true) ?? [];
                foreach ($lines as $lineData) {
                    if (!empty($lineData['description']) || !empty($lineData['amount'])) {
                        $invoice->lines()->create([
                            'description' => $lineData['description'] ?? '',
                            'qty' => $lineData['qty'] ?? 1,
                            'rate' => $lineData['rate'] ?? 0,
                            'amount' => $lineData['amount'] ?? 0,
                            'charge_code' => $lineData['charge_code'] ?? null,
                            'type' => $lineData['type'] ?? 'AR',
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice created successfully.',
                    'invoice' => $invoice->fresh()->load(['billTo', 'currency', 'office']),
                    'redirect' => route('accounting.invoices.edit', $invoice->id),
                ]);
            }

            if ($request->input('save_action') === 'save_new') {
                return redirect()->route('accounting.invoices.create')
                    ->with('success', 'Invoice created successfully.');
            }

            return redirect()->route('accounting.invoices.edit', $invoice->id)
                ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to create: ' . $e->getMessage()], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create invoice: ' . $e->getMessage()]);
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['billTo', 'currency', 'office', 'issuer', 'lines', 'payments', 'documents.uploader']);
        return view('accounting.invoice-show', compact('invoice'));
    }



    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $data = $request->validated();
        $data['discount_pct'] = $data['discount_pct'] ?? 0;
        $data['tax_pct'] = $data['tax_pct'] ?? 0;
        $data['shipping_amount'] = $data['shipping_amount'] ?? 0;
        $data['subtotal'] = $data['subtotal'] ?? 0;
        $data['tax_total'] = $data['tax_total'] ?? 0;
        $data['paid_amount'] = $data['paid_amount'] ?? 0;
        $data['balance_amount'] = $data['total_amount'] - ($data['paid_amount'] ?? 0);

        DB::beginTransaction();
        try {
            $invoice->update($data);

            if ($request->filled('lines_json')) {
                $invoice->lines()->delete();
                $lines = json_decode($request->input('lines_json'), true) ?? [];
                foreach ($lines as $lineData) {
                    if (!empty($lineData['description']) || !empty($lineData['amount'])) {
                        $invoice->lines()->create([
                            'description' => $lineData['description'] ?? '',
                            'qty' => $lineData['qty'] ?? 1,
                            'rate' => $lineData['rate'] ?? 0,
                            'amount' => $lineData['amount'] ?? 0,
                            'charge_code' => $lineData['charge_code'] ?? null,
                            'type' => $lineData['type'] ?? 'AR',
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice updated successfully.',
                    'invoice' => $invoice->fresh()->load(['billTo', 'currency', 'office']),
                ]);
            }

            if ($request->input('save_action') === 'save_new') {
                return redirect()->route('accounting.invoices.create')
                    ->with('success', 'Invoice updated successfully.');
            }

            return redirect()->route('accounting.invoices.index')
                ->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update: ' . $e->getMessage()], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update invoice: ' . $e->getMessage()]);
        }
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('accounting.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['lines', 'documents.uploader']);
        $tradePartners = TradePartner::all();
        $currencies = Currency::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        return view('accounting.invoice-create', compact('invoice', 'tradePartners', 'currencies', 'offices', 'users'));
    }

    /**
     * AJAX: Update color mark for an invoice.
     */
    public function updateColor(Request $request, Invoice $invoice)
    {
        $request->validate(['color' => 'nullable|string|max:7']);
        $invoice->update(['color' => $request->input('color')]);
        return response()->json(['success' => true, 'message' => 'Color updated.']);
    }

    /**
     * AJAX: Bulk delete invoices.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No invoices selected.']);
        }
        Invoice::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' invoice(s) deleted.']);
    }

    /**
     * AJAX: Batch update invoice status.
     */
    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:DRAFT,POSTED,PAID,PARTIAL,VOID',
        ]);

        $ids = $request->input('ids');
        $status = $request->input('status');

        $updated = Invoice::whereIn('id', $ids)->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => $updated . ' invoice(s) updated to ' . $status . '.',
        ]);
    }

    /**
     * Export invoices as CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Invoice::with(['billTo', 'currency', 'office']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('billTo', function($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="invoices_export_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($invoices) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($handle, ['Invoice No.', 'Date', 'Due Date', 'Party', 'Type', 'Status', 'Currency', 'Total Amount', 'Paid Amount', 'Balance', 'Office']);
            foreach ($invoices as $inv) {
                fputcsv($handle, [
                    $inv->invoice_no,
                    $inv->invoice_date ? $inv->invoice_date->format('Y-m-d') : '',
                    $inv->due_date ? $inv->due_date->format('Y-m-d') : '',
                    $inv->billTo->name ?? '',
                    $inv->type ?? 'AR',
                    $inv->status,
                    $inv->currency->code ?? 'USD',
                    number_format($inv->total_amount, 2),
                    number_format($inv->paid_amount, 2),
                    number_format($inv->balance_amount, 2),
                    $inv->office->code ?? '',
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Duplicate an invoice — loads create form with copied data.
     */
    public function duplicate(Invoice $invoice)
    {
        $invoice->load(['billTo', 'currency', 'office', 'issuer', 'lines']);
        $tradePartners = TradePartner::all();
        $currencies = Currency::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();

        // Generate a unique invoice_no for the copy
        $invoice->invoice_no = $invoice->invoice_no . '-COPY-' . time();

        return view('accounting.invoice-create', compact('invoice', 'tradePartners', 'currencies', 'offices', 'users'));
    }

    /**
     * AJAX: Upload a document to an invoice.
     */
    public function uploadDocument(Request $request, Invoice $invoice)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $file = $request->file('file');
        $path = $file->store('documents/invoices', 'public');

        $document = $invoice->documents()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        $document->load('uploader');

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $document->id,
                'file_name' => $document->file_name,
                'file_extension' => $document->file_extension,
                'file_size' => $document->file_size,
                'uploader_name' => $document->uploader?->name ?? 'N/A',
                'created_at' => $document->created_at?->format('Y-m-d') ?? '',
            ],
        ]);
    }

    /**
     * AJAX: Delete a document.
     */
    public function deleteDocument(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json(['success' => true, 'message' => 'Document deleted.']);
    }

    /**
     * Download a document.
     */
    public function downloadDocument(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->withErrors(['error' => 'File not found.']);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}

