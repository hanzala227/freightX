<?php

namespace App\Http\Controllers;

use App\Models\WarehouseReceiving;
use App\Models\WarehouseReceipt;
use App\Models\Office;
use App\Models\TradePartner;
use App\Models\User;
use App\Models\Document;
use App\Http\Requests\StoreWarehouseReceivingRequest;
use App\Http\Requests\UpdateWarehouseReceivingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WarehouseReceivingController extends Controller
{
    public function index(Request $request)
    {
        $query = WarehouseReceiving::with(['receipt.customer', 'receipt.shipper', 'operator', 'office', 'customer', 'billTo', 'shipFrom', 'trucker']);

        $this->applyFiltersToQuery($query, $request);

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['receiving_date', 'post_date', 'order_date', 'bl_no', 'container_no', 'status', 'pallet', 'created_at', 'receipt_no'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        if ($sortField === 'receipt_no') {
            $query->leftJoin('warehouse_receipts', 'warehouse_receivings.warehouse_receipt_id', '=', 'warehouse_receipts.id');
            $query->orderBy('warehouse_receipts.receipt_no', $sortDir);
        } else {
            $query->orderBy($sortField, $sortDir);
        }

        $receivings = $query->paginate(20)->withQueryString();

        return view('warehouse.receiving.list', compact('receivings'));
    }

    private function applyFiltersToQuery($query, Request $request)
    {
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('receipt', function($rq) use ($search) {
                    $rq->where('receipt_no', 'like', "%{$search}%")
                      ->orWhere('tracking_no', 'like', "%{$search}%")
                      ->orWhere('carrier_name', 'like', "%{$search}%");
                })
                ->orWhere('bl_no', 'like', "%{$search}%")
                ->orWhere('container_no', 'like', "%{$search}%")
                ->orWhere('quotation_no', 'like', "%{$search}%")
                ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('trucker', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('shipFrom', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('filter_receipt_no')) {
            $query->whereHas('receipt', fn($q) => $q->where('receipt_no', 'like', "%{$request->filter_receipt_no}%"));
        }
        if ($request->filled('filter_bl_no')) {
            $query->where('bl_no', 'like', "%{$request->filter_bl_no}%");
        }
        if ($request->filled('filter_container_no')) {
            $query->where('container_no', 'like', "%{$request->filter_container_no}%");
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_ship_from')) {
            $query->whereHas('shipFrom', fn($q) => $q->where('name', 'like', "%{$request->filter_ship_from}%"));
        }
        if ($request->filled('filter_office')) {
            $query->whereHas('office', fn($q) => $q->where('code', 'like', "%{$request->filter_office}%"));
        }
        if ($request->filled('filter_trucker')) {
            $query->whereHas('trucker', fn($q) => $q->where('name', 'like', "%{$request->filter_trucker}%"));
        }
        if ($request->filled('filter_status')) {
            $query->where('status', 'like', "%{$request->filter_status}%");
        }
        if ($request->filled('filter_pallet')) {
            $query->where('pallet', 'like', "%{$request->filter_pallet}%");
        }
        if ($request->filled('filter_post_date')) {
            $query->where('post_date', 'like', "%{$request->filter_post_date}%");
        }
        if ($request->filled('filter_receiving_date')) {
            $query->where('receiving_date', 'like', "%{$request->filter_receiving_date}%");
        }
        if ($request->filled('filter_order_date')) {
            $query->where('order_date', 'like', "%{$request->filter_order_date}%");
        }

        return $query;
    }

    public function updateColor(Request $request, WarehouseReceiving $warehouse_receiving)
    {
        $request->validate(['color' => 'nullable|string|max:20']);
        $warehouse_receiving->update(['color' => $request->color]);
        return response()->json(['success' => true, 'color' => $warehouse_receiving->color]);
    }

    public function exportCsv(Request $request)
    {
        $query = WarehouseReceiving::with(['receipt.customer', 'receipt.shipper', 'operator', 'office', 'customer', 'billTo', 'shipFrom', 'trucker']);
        $this->applyFiltersToQuery($query, $request);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="warehouse-receiving-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($query) {
            if (ob_get_level()) { ob_end_flush(); }
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, [
                'ID', 'Receipt No.', 'Customer', 'B/L No.', 'Container No.',
                'Ship From', 'Receiving Date', 'Post Date', 'Order Date',
                'Status', 'Pallet', 'Office', 'Trucker', 'Operator', 'Created At',
            ]);

            $query->latest()->chunk(200, function ($chunk) use ($file) {
                foreach ($chunk as $r) {
                    fputcsv($file, [
                        $r->id,
                        $r->receipt->receipt_no ?? 'WR-' . $r->id,
                        $r->customer->name ?? ($r->receipt->customer->name ?? ''),
                        $r->bl_no,
                        $r->container_no,
                        $r->shipFrom->name ?? ($r->receipt->shipper->name ?? ''),
                        $r->receiving_date ? $r->receiving_date->format('Y-m-d') : '',
                        $r->post_date ? $r->post_date->format('Y-m-d') : '',
                        $r->order_date ? $r->order_date->format('Y-m-d') : '',
                        $r->status,
                        $r->pallet,
                        $r->office->code ?? '',
                        $r->trucker->name ?? $r->receipt->carrier_name ?? '',
                        $r->operator->name ?? '',
                        $r->created_at->format('Y-m-d H:i'),
                    ]);
                }
                flush();
            });

            fclose($file);
        };

        return response()->streamDownload($callback, 'warehouse-receiving-' . now()->format('Y-m-d') . '.csv', $headers);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('receiving.index');
        }
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:warehouse_receivings,id']);
        $count = WarehouseReceiving::whereIn('id', $request->ids)->delete();
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $count . ' receiving record(s) deleted successfully.'
            ]);
        }
        return redirect()->route('receiving.index')->with('success', $count . ' record(s) deleted successfully.');
    }

    public function create()
    {
        $receipts = WarehouseReceipt::latest()->get();
        $users = User::all();
        $offices = Office::where('is_active', true)->get();
        $tradePartners = TradePartner::all();
        return view('warehouse.receiving.create', compact('receipts', 'users', 'offices', 'tradePartners'));
    }

    public function store(StoreWarehouseReceivingRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->filled('memos_json')) {
                $data['memos_data'] = json_decode($request->input('memos_json'), true);
            }

            $receiving = WarehouseReceiving::create($data);

            // Save items
            if ($request->filled('items_json')) {
                $items = json_decode($request->input('items_json'), true) ?? [];
                foreach ($items as $itemData) {
                    if (!empty($itemData['qty']) || !empty($itemData['sku_no'])) {
                        $receiving->items()->create([
                            'sku_no' => $itemData['sku_no'] ?? null,
                            'customer_po' => $itemData['customer_po'] ?? null,
                            'description' => $itemData['description'] ?? null,
                            'order_po_no' => $itemData['order_po_no'] ?? null,
                            'order_qty' => $itemData['order_qty'] ?? 0,
                            'qty' => $itemData['qty'] ?? 0,
                            'qty_unit' => $itemData['qty_unit'] ?? null,
                            'pack' => $itemData['pack'] ?? 0,
                            'pack_unit' => $itemData['pack_unit'] ?? null,
                            'pallet' => $itemData['pallet'] ?? null,
                            'weight_kg' => $itemData['weight_kg'] ?? 0,
                            'measure_cbm' => $itemData['measure_cbm'] ?? 0,
                            'inventory' => $itemData['inventory'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->input('save_action') === 'save_new') {
                return redirect()->route('receiving.create')
                    ->with('success', 'Receiving record created successfully.');
            }
            return redirect()->route('receiving.edit', $receiving->id)
                ->with('success', 'Receiving record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Failed to create receiving record: ' . $e->getMessage()]);
        }
    }

    public function show(WarehouseReceiving $warehouse_receiving)
    {
        $warehouse_receiving->load(['receipt', 'operator', 'office', 'customer', 'billTo', 'shipFrom', 'trucker']);
        return view('warehouse.receiving.show', compact('warehouse_receiving'));
    }

    public function edit(WarehouseReceiving $warehouse_receiving)
    {
        $warehouse_receiving->load(['items', 'documents.uploader']);
        $docData = $warehouse_receiving->documents->map(fn($d) => [
            'id' => $d->id,
            'file_name' => $d->file_name,
            'file_extension' => $d->file_extension,
            'file_size' => $d->file_size,
            'uploader_name' => $d->uploader->name ?? 'N/A',
            'created_at' => $d->created_at?->format('Y-m-d') ?? '',
        ])->values()->toArray();
        $memoData = $warehouse_receiving->memos_data ?? [];
        $itemData = $warehouse_receiving->items->map(fn($i) => [
            'sku_no' => $i->sku_no,
            'customer_po' => $i->customer_po,
            'description' => $i->description,
            'order_po_no' => $i->order_po_no,
            'order_qty' => $i->order_qty,
            'qty' => $i->qty,
            'qty_unit' => $i->qty_unit,
            'pack' => $i->pack,
            'pack_unit' => $i->pack_unit,
            'pallet' => $i->pallet,
            'weight_kg' => $i->weight_kg,
            'measure_cbm' => $i->measure_cbm,
            'inventory' => $i->inventory,
        ])->values()->toArray();
        $receipts = WarehouseReceipt::latest()->get();
        $users = User::all();
        $offices = Office::where('is_active', true)->get();
        $tradePartners = TradePartner::all();
        $receiving = $warehouse_receiving;
        return view('warehouse.receiving.create', compact('receiving', 'warehouse_receiving', 'receipts', 'users', 'offices', 'tradePartners', 'docData', 'memoData', 'itemData'));
    }

    public function update(UpdateWarehouseReceivingRequest $request, WarehouseReceiving $warehouse_receiving)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->filled('memos_json')) {
                $data['memos_data'] = json_decode($request->input('memos_json'), true);
            }

            $warehouse_receiving->update($data);

            // Replace items: delete old, insert new
            if ($request->filled('items_json')) {
                $warehouse_receiving->items()->delete();
                $items = json_decode($request->input('items_json'), true) ?? [];
                foreach ($items as $itemData) {
                    if (!empty($itemData['qty']) || !empty($itemData['sku_no'])) {
                        $warehouse_receiving->items()->create([
                            'sku_no' => $itemData['sku_no'] ?? null,
                            'customer_po' => $itemData['customer_po'] ?? null,
                            'description' => $itemData['description'] ?? null,
                            'order_po_no' => $itemData['order_po_no'] ?? null,
                            'order_qty' => $itemData['order_qty'] ?? 0,
                            'qty' => $itemData['qty'] ?? 0,
                            'qty_unit' => $itemData['qty_unit'] ?? null,
                            'pack' => $itemData['pack'] ?? 0,
                            'pack_unit' => $itemData['pack_unit'] ?? null,
                            'pallet' => $itemData['pallet'] ?? null,
                            'weight_kg' => $itemData['weight_kg'] ?? 0,
                            'measure_cbm' => $itemData['measure_cbm'] ?? 0,
                            'inventory' => $itemData['inventory'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->input('save_action') === 'save_new') {
                return redirect()->route('receiving.create')
                    ->with('success', 'Receiving record updated successfully.');
            }
            return redirect()->route('receiving.index')
                ->with('success', 'Receiving record updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Failed to update receiving record: ' . $e->getMessage()]);
        }
    }

    public function destroy(WarehouseReceiving $warehouse_receiving)
    {
        $warehouse_receiving->delete();
        return redirect()->route('receiving.index')
            ->with('success', 'Receiving record deleted successfully.');
    }

    public function uploadDocument(Request $request, WarehouseReceiving $warehouse_receiving)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $file = $request->file('file');
        $path = $file->store('documents/receivings', 'public');

        $document = $warehouse_receiving->documents()->create([
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

    public function deleteDocument(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return response()->json(['success' => true, 'message' => 'Document deleted.']);
    }

    public function downloadDocument(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->withErrors(['error' => 'File not found.']);
        }
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
