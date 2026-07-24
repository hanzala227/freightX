<?php

namespace App\Http\Controllers;

use App\Models\TruckShipment;
use App\Models\Office;
use App\Models\Port;
use App\Models\TradePartner;
use App\Models\Quotation;
use App\Models\User;
use App\Models\ContainerType;
use App\Services\TruckShipmentService;
use App\Http\Requests\StoreTruckShipmentRequest;
use App\Http\Requests\UpdateTruckShipmentRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TruckShipmentController extends Controller
{
    protected $truckShipmentService;

    public function __construct(TruckShipmentService $service)
    {
        $this->truckShipmentService = $service;
    }

    public function index(Request $request)
    {
        $query = TruckShipment::with([
            'office', 'operator', 'customer', 'shipper', 'consignee', 'trucker', 'pol', 'pod',
            'finalDestination', 'billTo', 'packageUnit', 'charges', 'containers'
        ]);

        // Quick search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mbl_no', 'like', "%{$search}%")
                  ->orWhere('hbl_no', 'like', "%{$search}%");
            });
        }

        // Column filters
        if ($request->filter_file_no) {
            $query->where('file_no', 'like', '%' . $request->filter_file_no . '%');
        }
        if ($request->filter_mbl_no) {
            $query->where('mbl_no', 'like', '%' . $request->filter_mbl_no . '%');
        }
        if ($request->filter_customer) {
            $query->where('customer_id', $request->filter_customer);
        }

        $sortField = $request->sort ?? 'created_at';
        $sortDir = $request->dir ?? 'desc';
        $allowedSorts = ['file_no', 'post_date', 'created_at', 'mbl_no', 'pkg_qty', 'weight_kg'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $shipments = $query->orderBy($sortField, $sortDir)->paginate($request->per_page ?? 20);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('truck.partials.list-rows', compact('shipments'))->render(),
                'pagination' => view('truck.partials.list-pagination', compact('shipments'))->render(),
                'total' => $shipments->total(),
                'from' => $shipments->firstItem(),
                'to' => $shipments->lastItem(),
            ]);
        }

        return view('truck.list', compact('shipments'));
    }

    public function myShipmentList(Request $request)
    {
        $query = TruckShipment::with([
            'office', 'operator', 'customer', 'shipper', 'consignee', 'trucker', 'pol', 'pod',
            'finalDestination', 'billTo', 'packageUnit', 'charges', 'containers'
        ]);

        // Filter to current user's shipments (OP or Sales), or unassigned
        $query->where(function ($q) {
            $q->where('op_id', auth()->id())
              ->orWhere('sales_id', auth()->id())
              ->orWhere(function ($sub) {
                  $sub->whereNull('op_id')->whereNull('sales_id');
              });
        });

        // Quick search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mbl_no', 'like', "%{$search}%")
                  ->orWhere('hbl_no', 'like', "%{$search}%");
            });
        }

        // Column filters
        if ($request->filter_file_no) {
            $query->where('file_no', 'like', '%' . $request->filter_file_no . '%');
        }
        if ($request->filter_customer) {
            $query->where('customer_id', $request->filter_customer);
        }

        $sortField = $request->sort ?? 'created_at';
        $sortDir = $request->dir ?? 'desc';
        $allowedSorts = ['file_no', 'post_date', 'created_at', 'mbl_no', 'pkg_qty', 'weight_kg'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $shipments = $query->orderBy($sortField, $sortDir)->paginate($request->per_page ?? 20)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('truck.partials.list-rows', compact('shipments'))->render(),
                'pagination' => view('truck.partials.list-pagination', compact('shipments'))->render(),
                'total' => $shipments->total(),
                'from' => $shipments->firstItem(),
                'to' => $shipments->lastItem(),
            ]);
        }

        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        $agents = TradePartner::all();
        $ports = Port::all();

        return view('truck.my-shipment-list', compact('shipments', 'offices', 'users', 'agents', 'ports'));
    }



    public function create(Request $request)
    {
        $offices = Office::where('is_active', true)->get();
        $ports = Port::all();
        $agents = TradePartner::all();
        $users = \App\Models\User::all();
        $packageUnits = \App\Models\PackageUnit::all();
        $quotations = Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])
            ->where('transport_mode', 'TRUCK')
            ->latest()
            ->get();
        $locations = TradePartner::whereIn('type', ['CF', 'CY', 'WH', 'WAREHOUSE', 'CFS'])
            ->orWhere('type', 'LOCATION')
            ->get();
        $truckers = TradePartner::whereIn('type', ['TRUCKER', 'CR', 'CARRIER', 'CS', 'PR'])->get();
        $currencies = \App\Models\Currency::all();
        $containerTypes = ContainerType::all();

        $page = $request->segment(2);

        // Handle copy from existing shipment
        $copyShipment = null;
        if ($request->has('copy')) {
            $copyId = $request->query('copy');
            $copyShipment = TruckShipment::with([
                'office', 'operator', 'customer', 'shipper', 'consignee', 'trucker',
                'pol', 'pod', 'finalDestination', 'billTo', 'packageUnit',
                'memos', 'containers', 'charges'
            ])->find($copyId);
        }

        return view('truck.create', compact(
            'offices', 'ports', 'agents', 'users', 'packageUnits',
            'quotations', 'locations', 'truckers', 'currencies', 'page', 'containerTypes',
            'copyShipment'
        ));
    }

    public function store(StoreTruckShipmentRequest $request)
    {
        $shipment = $this->truckShipmentService->store($request->validated());

        return redirect()->route('truck.edit', $shipment->id)
            ->with('success', 'Truck Shipment created successfully.');
    }

    public function edit(TruckShipment $truckShipment)
    {
        $truckShipment->load(['charges', 'documents.uploader', 'workOrders', 'memos', 'containers', 'statusLogs']);

        $offices = Office::where('is_active', true)->get();
        $ports = Port::all();
        $agents = TradePartner::all();
        $users = \App\Models\User::all();
        $packageUnits = \App\Models\PackageUnit::all();
        $quotations = Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])
            ->where('transport_mode', 'TRUCK')
            ->latest()
            ->get();
        $locations = TradePartner::whereIn('type', ['CF', 'CY', 'WH', 'WAREHOUSE', 'CFS'])
            ->orWhere('type', 'LOCATION')
            ->get();
        $truckers = TradePartner::whereIn('type', ['TRUCKER', 'CR', 'CARRIER', 'CS', 'PR'])->get();
        $currencies = \App\Models\Currency::all();

        $containerTypes = ContainerType::all();

        return view('truck.create', compact(
            'truckShipment', 'offices', 'ports', 'agents', 'users', 'packageUnits',
            'quotations', 'locations', 'truckers', 'currencies', 'containerTypes'
        ));
    }

    public function update(UpdateTruckShipmentRequest $request, TruckShipment $truckShipment)
    {
        $this->truckShipmentService->update($truckShipment, $request->validated());

        return back()->with('success', 'Truck Shipment updated successfully.');
    }

    public function updateColor(Request $request, TruckShipment $truckShipment)
    {
        $request->validate([
            'color' => 'nullable|string|max:20',
        ]);

        $truckShipment->update(['color' => $request->color]);

        return response()->json(['success' => true, 'color' => $truckShipment->color]);
    }



    public function destroy(TruckShipment $truckShipment)
    {
        try {
            $truckShipment->delete();
            return response()->json(['success' => true, 'message' => 'Truck Shipment deleted.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete: ' . $e->getMessage()], 500);
        }
    }

    // ========== Bulk Actions ==========

    public function bulkDelete(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('truck.my-shipment-list');
        }

        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            TruckShipment::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => count($ids) . ' shipment(s) deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete: ' . $e->getMessage()], 500);
        }
    }

    public function bulkBlock(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('truck.my-shipment-list');
        }

        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            TruckShipment::whereIn('id', $ids)->update(['is_blocked' => true]);
            return response()->json(['success' => true, 'message' => count($ids) . ' shipment(s) blocked.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Block failed: ' . $e->getMessage()], 500);
        }
    }

    public function bulkUnblock(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('truck.my-shipment-list');
        }

        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            TruckShipment::whereIn('id', $ids)->update(['is_blocked' => false]);
            return response()->json(['success' => true, 'message' => count($ids) . ' shipment(s) unblocked.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unblock failed: ' . $e->getMessage()], 500);
        }
    }

    public function exportCsv(Request $request)
    {
        $query = TruckShipment::with([
            'office', 'operator', 'customer', 'shipper', 'consignee', 'trucker', 'pol', 'pod',
            'finalDestination', 'billTo', 'packageUnit', 'charges', 'containers'
        ]);

        $query->where(function ($q) {
            $q->where('op_id', auth()->id())
              ->orWhere('sales_id', auth()->id())
              ->orWhere(function ($sub) {
                  $sub->whereNull('op_id')->whereNull('sales_id');
              });
        });

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mbl_no', 'like', "%{$search}%")
                  ->orWhere('hbl_no', 'like', "%{$search}%");
            });
        }

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="truck-shipments-' . date('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($query) {
            if (ob_get_level()) { ob_end_flush(); }
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, [
                'File No.', 'Post Date', 'Customer', 'Trucker', 'MB/L No.', 'HB/L No.',
                'Package Qty', 'Weight (Kg)', 'POL', 'POD', 'Final Destination',
                'AR Balance', 'Delivered', 'Internal Remark'
            ]);

            $query->latest()->chunk(200, function ($chunk) use ($handle) {
                foreach ($chunk as $s) {
                    $arTotal = $s->charges->where('type', 'AR')->sum('amount');
                    fputcsv($handle, [
                        $s->file_no,
                        $s->post_date ? $s->post_date->format('Y-m-d') : '',
                        $s->customer->name ?? '',
                        $s->trucker->name ?? '',
                        $s->mbl_no ?? '',
                        $s->hbl_no ?? '',
                        $s->pkg_qty ?? 0,
                        $s->weight_kg ?? 0,
                        $s->pol->name ?? '',
                        $s->pod->name ?? '',
                        $s->finalDestination->name ?? '',
                        number_format($arTotal, 2),
                        $s->is_delivered ? 'Yes' : 'No',
                        $s->internal_remark ?? '',
                    ]);
                }
                flush();
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, 'truck-shipments-' . date('Y-m-d') . '.csv', $headers);
    }

    // ========== Document Management ==========

    public function uploadDocument(Request $request, TruckShipment $truckShipment)
    {
        $request->validate([
            'document' => 'required|file|max:10240',
            'file_name' => 'nullable|string|max:255',
            'document_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('document');
        $fileName = $request->file_name ?? $file->getClientOriginalName();
        $path = $file->store('documents/truck-shipments/' . $truckShipment->id, 'public');

        $document = $truckShipment->documents()->create([
            'file_name' => $fileName,
            'file_path' => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'document_type' => $request->document_type,
            'description' => $request->description,
            'uploaded_by' => auth()->id(),
        ]);

        $document->load('uploader');

        if ($request->wantsJson()) {
            return response()->json($document);
        }

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument($document)
    {
        $doc = \App\Models\Document::findOrFail($document);
        
        \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return response()->json(['success' => true]);
    }

    public function downloadDocument($document)
    {
        $doc = \App\Models\Document::findOrFail($document);
        
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
            abort(404, 'File not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($doc->file_path, $doc->file_name);
    }

    // ========== Memo Management ==========

    public function addMemo(Request $request, TruckShipment $truckShipment)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $memo = $truckShipment->memos()->create([
            'subject' => $request->subject,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        $memo->load('user');

        return response()->json($memo);
    }

    public function updateMemo(Request $request, $memo)
    {
        $memo = \App\Models\TruckShipmentMemo::findOrFail($memo);

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $memo->update([
            'subject' => $request->subject,
            'content' => $request->content,
        ]);

        return response()->json($memo);
    }

    public function deleteMemo($memo)
    {
        $memo = \App\Models\TruckShipmentMemo::findOrFail($memo);
        $memo->delete();

        return response()->json(['success' => true]);
    }

    // ========== Charge Management ==========

    public function storeCharge(Request $request, TruckShipment $truckShipment)
    {
        $data = $request->validate([
            'type' => 'required|string|in:AR,AP',
            'charge_code' => 'nullable|string|max:50',
            'charge_name' => 'nullable|string|max:255',
            'bill_to_id' => 'nullable|exists:trade_partners,id',
            'vendor_id' => 'nullable|exists:trade_partners,id',
            'qty' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'currency_id' => 'nullable|exists:currencies,id',
            'rate' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'remark' => 'nullable|string',
        ]);

        $data['chargeable_type'] = get_class($truckShipment);
        $data['chargeable_id'] = $truckShipment->id;

        $charge = $truckShipment->charges()->create($data);
        $charge->load('currency');

        return response()->json(['success' => true, 'charge' => $charge]);
    }

    public function updateCharge(Request $request, $charge)
    {
        $charge = \App\Models\Charge::findOrFail($charge);

        $data = $request->validate([
            'type' => 'nullable|string|in:AR,AP',
            'charge_code' => 'nullable|string|max:50',
            'charge_name' => 'nullable|string|max:255',
            'bill_to_id' => 'nullable|exists:trade_partners,id',
            'vendor_id' => 'nullable|exists:trade_partners,id',
            'qty' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'currency_id' => 'nullable|exists:currencies,id',
            'rate' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'remark' => 'nullable|string',
        ]);

        $charge->update($data);

        return response()->json(['success' => true, 'charge' => $charge->fresh()->load('currency')]);
    }

    public function deleteCharge($charge)
    {
        $charge = \App\Models\Charge::findOrFail($charge);
        $charge->delete();

        return response()->json(['success' => true]);
    }

    public function createInvoiceFromCharges(Request $request, TruckShipment $truckShipment)
    {
        $charges = $truckShipment->charges()->where('is_invoiced', false)->get();

        if ($charges->count() === 0) {
            return response()->json(['success' => false, 'message' => 'No uninvoiced charges found.'], 400);
        }

        $invNo = 'INV-' . strtoupper(uniqid());
        foreach ($charges as $charge) {
            $charge->update([
                'is_invoiced' => true,
                'invoice_no' => $invNo,
                'invoice_date' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'invoice_no' => $invNo,
            'message' => "Created invoice {$invNo} for " . $charges->count() . " charges.",
        ]);
    }
}
