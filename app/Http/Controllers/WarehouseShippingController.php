<?php

namespace App\Http\Controllers;

use App\Models\WarehouseShipping;
use App\Models\TradePartner;
use App\Models\Office;
use App\Models\User;
use App\Models\Document;
use App\Http\Requests\StoreWarehouseShippingRequest;
use App\Http\Requests\UpdateWarehouseShippingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WarehouseShippingController extends Controller
{
    public function index(Request $request)
    {
        $query = WarehouseShipping::with(['warehouse', 'customer', 'office', 'operator', 'billTo', 'shipTo', 'trucker']);

        $this->applyFiltersToQuery($query, $request);

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['shipping_no', 'shipping_date', 'order_date', 'out_date', 'status', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $shippings = $query->orderBy($sortField, $sortDir)->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return view('warehouse.shipping.list', compact('shippings'));
        }

        $users = User::all();
        $offices = Office::where('is_active', true)->get();
        $tradePartners = TradePartner::all();

        return view('warehouse.shipping.list', compact('shippings', 'users', 'offices', 'tradePartners'));
    }

    private function applyFiltersToQuery($query, Request $request)
    {
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('shipping_no', 'like', "%{$search}%")
                  ->orWhere('order_no', 'like', "%{$search}%")
                  ->orWhere('truck_bl_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($rq) => $rq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('warehouse', fn($rq) => $rq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('filter_shipping_no')) {
            $query->where('shipping_no', 'like', "%{$request->filter_shipping_no}%");
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_warehouse')) {
            $query->whereHas('warehouse', fn($q) => $q->where('name', 'like', "%{$request->filter_warehouse}%"));
        }
        if ($request->filled('filter_office')) {
            $query->whereHas('office', fn($q) => $q->where('code', 'like', "%{$request->filter_office}%"));
        }
        if ($request->filled('filter_status')) {
            $query->where('status', 'like', "%{$request->filter_status}%");
        }
        if ($request->filled('filter_shipping_date')) {
            $query->where('shipping_date', 'like', "%{$request->filter_shipping_date}%");
        }
        if ($request->filled('filter_out_date')) {
            $query->where('out_date', 'like', "%{$request->filter_out_date}%");
        }
        if ($request->filled('filter_order_no')) {
            $query->where('order_no', 'like', "%{$request->filter_order_no}%");
        }
        if ($request->filled('filter_order_date')) {
            $query->where('order_date', 'like', "%{$request->filter_order_date}%");
        }
        if ($request->filled('filter_truck_bl_no')) {
            $query->where('truck_bl_no', 'like', "%{$request->filter_truck_bl_no}%");
        }
        if ($request->filled('filter_trucker')) {
            $query->whereHas('trucker', fn($q) => $q->where('name', 'like', "%{$request->filter_trucker}%"));
        }
        if ($request->filled('filter_ship_to')) {
            $query->whereHas('shipTo', fn($q) => $q->where('name', 'like', "%{$request->filter_ship_to}%"));
        }
        if ($request->filled('filter_pallet')) {
            $query->where('pallet', 'like', "%{$request->filter_pallet}%");
        }

        return $query;
    }

    public function create()
    {
        $warehouses = TradePartner::whereIn('type', ['WH', 'WAREHOUSE'])->orderBy('name')->get();
        $tradePartners = TradePartner::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        return view('warehouse.shipping.create', compact('warehouses', 'tradePartners', 'offices', 'users'));
    }

    public function store(StoreWarehouseShippingRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->filled('memos_json')) {
                $data['memos_data'] = json_decode($request->input('memos_json'), true);
            }
            if ($request->filled('items_json')) {
                $data['items_data'] = json_decode($request->input('items_json'), true);
            }

            $shipping = WarehouseShipping::create($data);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shipping record created successfully.',
                    'shipping' => $shipping->fresh()->load(['warehouse', 'customer', 'office']),
                    'redirect' => route('shipping.edit', $shipping->id),
                ]);
            }

            if ($request->input('save_action') === 'save_new') {
                return redirect()->route('shipping.create')
                    ->with('success', 'Shipping record created successfully.');
            }
            return redirect()->route('shipping.edit', $shipping->id)
                ->with('success', 'Shipping record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to create: ' . $e->getMessage()], 422);
            }
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Failed to create shipping record: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $shipping = WarehouseShipping::with(['warehouse', 'customer', 'office', 'operator'])->findOrFail($id);
        return view('warehouse.shipping.show', compact('shipping'));
    }

    public function edit($id)
    {
        $shipping = WarehouseShipping::with(['documents.uploader'])->findOrFail($id);
        $docData = $shipping->documents->map(fn($d) => [
            'id' => $d->id,
            'file_name' => $d->file_name,
            'file_extension' => $d->file_extension,
            'file_size' => $d->file_size,
            'uploader_name' => $d->uploader->name ?? 'N/A',
            'created_at' => $d->created_at?->format('Y-m-d') ?? '',
        ])->values()->toArray();
        $memoData = $shipping->memos_data ?? [];
        $warehouses = TradePartner::whereIn('type', ['WH', 'WAREHOUSE'])->orderBy('name')->get();
        $tradePartners = TradePartner::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        return view('warehouse.shipping.create', compact(
            'shipping', 'warehouses', 'tradePartners', 'offices', 'users', 'docData', 'memoData'
        ));
    }

    public function update(UpdateWarehouseShippingRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $shipping = WarehouseShipping::findOrFail($id);
            $data = $request->validated();

            if ($request->filled('memos_json')) {
                $data['memos_data'] = json_decode($request->input('memos_json'), true);
            }
            if ($request->filled('items_json')) {
                $data['items_data'] = json_decode($request->input('items_json'), true);
            }

            $shipping->update($data);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shipping record updated successfully.',
                    'shipping' => $shipping->fresh()->load(['warehouse', 'customer', 'office']),
                ]);
            }

            if ($request->input('save_action') === 'save_new') {
                return redirect()->route('shipping.create')
                    ->with('success', 'Shipping record updated successfully.');
            }
            return redirect()->route('shipping.index')
                ->with('success', 'Shipping record updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update: ' . $e->getMessage()], 422);
            }
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Failed to update shipping record: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $shipping = WarehouseShipping::findOrFail($id);
        $shipping->delete();
        return redirect()->route('shipping.index')
            ->with('success', 'Shipping record deleted successfully.');
    }

    public function updateColor(Request $request, WarehouseShipping $shipping)
    {
        $request->validate(['color' => 'nullable|string|max:20']);
        $shipping->update(['color' => $request->color]);
        return response()->json(['success' => true, 'color' => $shipping->color]);
    }

    public function exportCsv(Request $request)
    {
        $query = WarehouseShipping::with(['warehouse', 'customer', 'office', 'operator', 'trucker']);
        $this->applyFiltersToQuery($query, $request);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="warehouse-shipping-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($query) {
            if (ob_get_level()) { ob_end_flush(); }
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, [
                'ID', 'Shipping No.', 'Customer', 'Warehouse', 'Post Date', 'Out Date',
                'Order Date', 'Order No.', 'Truck B/L', 'Status', 'Pallet', 'Office', 'Trucker', 'Operator', 'Created At',
            ]);

            $query->latest()->chunk(200, function ($chunk) use ($file) {
                foreach ($chunk as $s) {
                    fputcsv($file, [
                        $s->id,
                        $s->shipping_no,
                        $s->customer->name ?? '',
                        $s->warehouse->name ?? '',
                        $s->shipping_date ? $s->shipping_date->format('Y-m-d') : '',
                        $s->out_date ? $s->out_date->format('Y-m-d') : '',
                        $s->order_date ? $s->order_date->format('Y-m-d') : '',
                        $s->order_no,
                        $s->truck_bl_no,
                        $s->status,
                        $s->pallet,
                        $s->office->code ?? '',
                        $s->trucker->name ?? '',
                        $s->operator->name ?? '',
                        $s->created_at->format('Y-m-d H:i'),
                    ]);
                }
                flush();
            });
            fclose($file);
        };

        return response()->streamDownload($callback, 'warehouse-shipping-' . now()->format('Y-m-d') . '.csv', $headers);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('shipping.index');
        }
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:warehouse_shippings,id']);
        $count = WarehouseShipping::whereIn('id', $request->ids)->delete();
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $count . ' shipping record(s) deleted successfully.'
            ]);
        }
        return redirect()->route('shipping.index')->with('success', $count . ' record(s) deleted successfully.');
    }

    public function uploadDocument(Request $request, WarehouseShipping $shipping)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $file = $request->file('file');
        $path = $file->store('documents/shippings', 'public');

        $document = $shipping->documents()->create([
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
