<?php

namespace App\Http\Controllers;

use App\Models\WarehouseAutomobile;
use App\Models\Office;
use App\Models\User;
use App\Models\TradePartner;
use App\Http\Requests\StoreWarehouseAutomobileRequest;
use App\Http\Requests\UpdateWarehouseAutomobileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseAutomobileController extends Controller
{
    public function index(Request $request)
    {
        $query = WarehouseAutomobile::with(['receiver', 'customer', 'office', 'creator']);

        // Quick search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vin_no', 'like', "%{$search}%")
                  ->orWhere('wh_receipt_no', 'like', "%{$search}%")
                  ->orWhere('maker', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('engine_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter row filters (matching Ocean Import pattern)
        if ($request->filled('filter_vin_no')) {
            $query->where('vin_no', 'like', "%{$request->filter_vin_no}%");
        }
        if ($request->filled('filter_maker')) {
            $query->where('maker', 'like', "%{$request->filter_maker}%");
        }
        if ($request->filled('filter_model')) {
            $query->where('model', 'like', "%{$request->filter_model}%");
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_year')) {
            $query->where('year', 'like', "%{$request->filter_year}%");
        }
        if ($request->filled('filter_office')) {
            $query->whereHas('office', fn($q) => $q->where('code', 'like', "%{$request->filter_office}%"));
        }
        if ($request->filled('filter_engine_no')) {
            $query->where('engine_no', 'like', "%{$request->filter_engine_no}%");
        }
        if ($request->filled('filter_received_date')) {
            $query->where('received_date', 'like', "%{$request->filter_received_date}%");
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['vin_no', 'wh_receipt_no', 'received_date', 'maker', 'year', 'model', 'engine_no', 'manufacture_date', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $pageSize = $request->input('limit', 20);
        $automobiles = $query->orderBy($sortField, $sortDir)->paginate($pageSize)->withQueryString();

        if ($request->ajax()) {
            return response()->json($automobiles);
        }

        $offices = Office::where('is_active', true)->get();
        $users = User::all();

        return view('warehouse.automobile.index', compact('automobiles', 'offices', 'users'));
    }

    public function create(Request $request)
    {
        $customers = TradePartner::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();

        $copyFrom = null;
        if ($request->has('copy')) {
            $copyFrom = WarehouseAutomobile::find($request->copy);
        }

        return view('warehouse.automobile.create', compact('customers', 'offices', 'users', 'copyFrom'));
    }

    public function store(StoreWarehouseAutomobileRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        DB::beginTransaction();
        try {
            WarehouseAutomobile::create($data);
            DB::commit();
            return redirect()->route('warehouse.automobile.index')
                ->with('success', 'Automobile created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Failed to create automobile: ' . $e->getMessage()]);
        }
    }

    public function show(WarehouseAutomobile $warehouseAutomobile)
    {
        $warehouseAutomobile->load(['receiver', 'customer', 'office', 'creator']);
        $customers = TradePartner::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        return view('warehouse.automobile.show', compact('warehouseAutomobile', 'customers', 'offices', 'users'));
    }

    public function edit(WarehouseAutomobile $warehouseAutomobile)
    {
        $customers = TradePartner::all();
        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        return view('warehouse.automobile.edit', compact('warehouseAutomobile', 'customers', 'offices', 'users'));
    }

    public function update(UpdateWarehouseAutomobileRequest $request, WarehouseAutomobile $warehouseAutomobile)
    {
        DB::beginTransaction();
        try {
            $warehouseAutomobile->update($request->validated());
            DB::commit();
            return redirect()->route('warehouse.automobile.index')
                ->with('success', 'Automobile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Failed to update automobile: ' . $e->getMessage()]);
        }
    }

    public function destroy(WarehouseAutomobile $warehouseAutomobile)
    {
        $warehouseAutomobile->delete();
        return redirect()->route('warehouse.automobile.index')
            ->with('success', 'Automobile deleted successfully.');
    }

    public function updateColor(Request $request, WarehouseAutomobile $warehouseAutomobile)
    {
        $request->validate(['color' => 'nullable|string|max:20']);
        $warehouseAutomobile->update(['color' => $request->color]);
        return response()->json(['success' => true, 'color' => $warehouseAutomobile->color]);
    }

    public function toggleBlock(Request $request, WarehouseAutomobile $warehouseAutomobile)
    {
        $request->validate(['is_blocked' => 'required|boolean']);
        $warehouseAutomobile->update(['is_blocked' => $request->is_blocked]);
        return response()->json([
            'success' => true,
            'message' => $request->is_blocked ? 'Automobile blocked.' : 'Automobile unblocked.',
            'is_blocked' => $warehouseAutomobile->is_blocked,
        ]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('warehouse.automobile.index');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:warehouse_automobiles,id',
        ]);

        $count = WarehouseAutomobile::whereIn('id', $request->ids)->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} automobile(s) deleted successfully."
            ]);
        }

        return redirect()->route('warehouse.automobile.index')
            ->with('success', "{$count} automobile(s) deleted successfully.");
    }

    public function bulkBlock(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('warehouse.automobile.index');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:warehouse_automobiles,id',
        ]);

        WarehouseAutomobile::whereIn('id', $request->ids)->update(['is_blocked' => true]);
        $count = count($request->ids);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} automobile(s) blocked successfully."
            ]);
        }

        return redirect()->route('warehouse.automobile.index')
            ->with('success', "{$count} automobile(s) blocked successfully.");
    }

    public function bulkUnblock(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('warehouse.automobile.index');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:warehouse_automobiles,id',
        ]);

        WarehouseAutomobile::whereIn('id', $request->ids)->update(['is_blocked' => false]);
        $count = count($request->ids);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} automobile(s) unblocked successfully."
            ]);
        }

        return redirect()->route('warehouse.automobile.index')
            ->with('success', "{$count} automobile(s) unblocked successfully.");
    }

    public function exportCsv(Request $request)
    {
        $query = WarehouseAutomobile::with(['receiver', 'customer', 'office']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vin_no', 'like', "%{$search}%")
                  ->orWhere('wh_receipt_no', 'like', "%{$search}%");
            });
        }

        // Apply filter row filters (matching index())
        if ($request->filled('filter_vin_no')) {
            $query->where('vin_no', 'like', "%{$request->filter_vin_no}%");
        }
        if ($request->filled('filter_maker')) {
            $query->where('maker', 'like', "%{$request->filter_maker}%");
        }
        if ($request->filled('filter_model')) {
            $query->where('model', 'like', "%{$request->filter_model}%");
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_year')) {
            $query->where('year', 'like', "%{$request->filter_year}%");
        }
        if ($request->filled('filter_office')) {
            $query->whereHas('office', fn($q) => $q->where('code', 'like', "%{$request->filter_office}%"));
        }
        if ($request->filled('filter_engine_no')) {
            $query->where('engine_no', 'like', "%{$request->filter_engine_no}%");
        }
        if ($request->filled('filter_received_date')) {
            $query->where('received_date', 'like', "%{$request->filter_received_date}%");
        }

        $automobiles = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="warehouse-automobiles-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($query) {
            // Disable output buffering for immediate download
            @ob_end_flush();
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, [
                'VIN No.', 'WH Receipt No.', 'Received By', 'Received Date',
                'Customer', 'Maker', 'Year', 'Model', 'Engine No.',
                'Manufacture Date', 'Title Received', 'Office', 'Color', 'Blocked',
            ]);

            // Stream in chunks for large datasets
            $query->latest()->chunk(200, function ($chunk) use ($file) {
                foreach ($chunk as $a) {
                    fputcsv($file, [
                        $a->vin_no,
                        $a->wh_receipt_no ?? '',
                        $a->receiver->name ?? '',
                        $a->received_date ? $a->received_date->format('m-d-Y') : '',
                        $a->customer->name ?? '',
                        $a->maker ?? '',
                        $a->year ?? '',
                        $a->model ?? '',
                        $a->engine_no ?? '',
                        $a->manufacture_date ? $a->manufacture_date->format('m-d-Y') : '',
                        $a->title_received ? 'Yes' : 'No',
                        $a->office->code ?? '',
                        $a->color ?? '',
                        $a->is_blocked ? 'Yes' : 'No',
                    ]);
                }
                flush();
            });

            fclose($file);
        };

        // Use streamDownload for better browser download handling
        return response()->streamDownload($callback, 'warehouse-automobiles-' . now()->format('Y-m-d') . '.csv', $headers);
    }

    public function getDocuments(WarehouseAutomobile $warehouse_automobile)
    {
        return response()->json([
            'success' => true,
            'documents' => $warehouse_automobile->documents()->latest()->get()
        ]);
    }

    public function uploadDocument(Request $request, WarehouseAutomobile $warehouse_automobile)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'document_type' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $path = $file->store('warehouse/automobile/documents', 'public');

        $document = $warehouse_automobile->documents()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'document_type' => $request->input('document_type', 'Others'),
            'uploaded_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully.',
            'document' => $document
        ]);
    }

    public function deleteDocument(\App\Models\Document $document)
    {
        if ($document->documentable_type !== WarehouseAutomobile::class) {
            abort(403);
        }

        \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.'
        ]);
    }

    public function updateDocumentPurpose(Request $request, \App\Models\Document $document)
    {
        if ($document->documentable_type !== WarehouseAutomobile::class) {
            abort(403);
        }

        $request->validate([
            'document_type' => 'required|string'
        ]);

        $document->update([
            'document_type' => $request->document_type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purpose updated successfully.',
            'document' => $document
        ]);
    }

    public function downloadAllDocuments(WarehouseAutomobile $warehouse_automobile)
    {
        $documents = $warehouse_automobile->documents;
        if ($documents->isEmpty()) {
            return back()->with('error', 'No documents available to download.');
        }

        $zipFileName = 'automobile_' . $warehouse_automobile->id . '_documents.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($documents as $doc) {
                $filePath = storage_path('app/public/' . $doc->file_path);
                if (file_exists($filePath)) {
                    // Prepend ID to avoid name collision if they have same name
                    $zip->addFile($filePath, $doc->id . '_' . $doc->file_name);
                }
            }
            $zip->close();
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
