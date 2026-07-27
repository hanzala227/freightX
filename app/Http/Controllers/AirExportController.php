<?php

namespace App\Http\Controllers;

use App\Models\AirExport;
use App\Models\Office;
use App\Models\Port;
use App\Models\TradePartner;
use App\Services\AirExportService;
use App\Http\Requests\StoreAirExportRequest;
use App\Http\Requests\UpdateAirExportRequest;
use App\Models\Currency;
use App\Models\Charge;
use Illuminate\Http\Request;

class AirExportController extends Controller
{
    protected $airExportService;

    public function __construct(AirExportService $service)
    {
        $this->airExportService = $service;
    }

    public function index(Request $request)
    {
        $query = AirExport::with([
            'office', 'operator', 'carrier',
            'depPort', 'dstPort',
            'forwardingAgent', 'overseaAgent', 'shipper', 'dmCustomer',
            'hbls',
        ]);

        $this->applyFiltersToQuery($query, $request);

        $sortField = $request->get('sort', 'created_at');
        $sortDir   = $request->get('dir', 'desc');
        $allowedSorts = ['file_no', 'mawb_no', 'etd', 'eta', 'created_at', 'post_date', 'flight_no'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $shipments = $query->orderBy($sortField, $sortDir)->paginate(20)->withQueryString();

        $offices = Office::where('is_active', true)->get();
        $users = \App\Models\User::all();
        $agents = TradePartner::whereNotNull('name')->where(function($q) { $q->where('type', 'carrier')->orWhereNull('type'); })->orderBy('name')->get();
        $ports = Port::all();

        return view('air-export.list', compact('shipments', 'offices', 'users', 'agents', 'ports'));
    }

    private function applyFiltersToQuery($query, Request $request)
    {
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mawb_no', 'like', "%{$search}%")
                  ->orWhere('flight_no', 'like', "%{$search}%");
            });
        }

        // Per-column filters from filter row
        if ($request->filled('filter_file_no')) {
            $query->where('file_no', 'like', "%{$request->filter_file_no}%");
        }
        if ($request->filled('filter_mawb_no')) {
            $query->where('mawb_no', 'like', "%{$request->filter_mawb_no}%");
        }
        if ($request->filled('filter_office')) {
            $query->whereHas('office', fn($q) => $q->where('code', 'like', "%{$request->filter_office}%"));
        }
        if ($request->filled('filter_shipper')) {
            $query->whereHas('shipper', fn($q) => $q->where('name', 'like', "%{$request->filter_shipper}%"));
        }
        if ($request->filled('filter_etd')) {
            $query->where('etd', 'like', "%{$request->filter_etd}%");
        }
        if ($request->filled('filter_eta')) {
            $query->where('eta', 'like', "%{$request->filter_eta}%");
        }
        if ($request->filled('filter_dep')) {
            $query->whereHas('depPort', fn($q) => $q->where('name', 'like', "%{$request->filter_dep}%"));
        }
        if ($request->filled('filter_dst')) {
            $query->whereHas('dstPort', fn($q) => $q->where('name', 'like', "%{$request->filter_dst}%"));
        }
        if ($request->filled('filter_oa')) {
            $query->whereHas('overseaAgent', fn($q) => $q->where('name', 'like', "%{$request->filter_oa}%"));
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('dmCustomer', fn($q) => $q->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_oa')) {
            $query->whereHas('overseaAgent', fn($q) => $q->where('name', 'like', "%{$request->filter_oa}%"));
        }

        // Advanced filters
        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }
        if ($request->filled('op_id')) {
            $query->where('op_id', $request->op_id);
        }
        if ($request->filled('carrier_id')) {
            $query->where('carrier_id', $request->carrier_id);
        }
        if ($request->filled('dep_port_id')) {
            $query->where('dep_port_id', $request->dep_port_id);
        }
        if ($request->filled('dst_port_id')) {
            $query->where('dst_port_id', $request->dst_port_id);
        }
        if ($request->filled('etd_from')) {
            $query->where('etd', '>=', $request->etd_from);
        }
        if ($request->filled('etd_to')) {
            $query->where('etd', '<=', $request->etd_to);
        }
        if ($request->filled('eta_from')) {
            $query->where('eta', '>=', $request->eta_from);
        }
        if ($request->filled('eta_to')) {
            $query->where('eta', '<=', $request->eta_to);
        }

        return $query;
    }

    public function create(Request $request)
    {
        $offices = Office::where('is_active', true)->get();
        $ports = Port::all();
        $agents = TradePartner::all();
        $users = \App\Models\User::all();
        $packageUnits = \App\Models\PackageUnit::all();
        $currencies = Currency::all();

        $page = $request->segment(2);
        $quotations = \App\Models\Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])->latest()->get();

        if ($request->has('copy')) {
            $airExport = AirExport::with(['hbls', 'charges.currency'])->findOrFail($request->copy);
            // Null the ID so the create view treats this as a new record (POST), not an edit (PUT)
            $airExport->id = null;
            $airExport->mawb_no = null;
            $airExport->file_no = null;
            $airExport->created_at = null;
            $chargesData = $airExport->charges->isNotEmpty()
                ? $airExport->charges->map(fn($c) => [
                    'id' => $c->id,
                    'selected' => false,
                    'charge_code' => $c->charge_code,
                    'charge_name' => $c->charge_name,
                    'currency' => $c->currency->code ?? 'USD',
                    'currency_id' => $c->currency_id,
                    'rate' => $c->rate,
                    'qty' => $c->qty,
                    'amount' => $c->amount,
                    'total_amount' => $c->total_amount ?? $c->amount,
                    'pc' => $c->pc,
                    'pr' => $c->type === 'AP' || $c->type === 'origin_cost' ? 'Pay' : 'Rec',
                    'type' => $c->type,
                    'vendor_id' => $c->vendor_id,
                    'bill_to_id' => $c->bill_to_id,
                    'invoice_no' => $c->invoice_no ?? '',
                    'remark' => $c->remark ?? '',
                    'created_at' => $c->created_at ? $c->created_at->format('m/d/Y') : '',
                ])
                : collect();
            return view('air-export.create', compact('airExport', 'offices', 'ports', 'agents', 'users', 'packageUnits', 'currencies', 'page', 'quotations', 'chargesData'));
        }

        $chargesData = collect();

        return view('air-export.create', compact('offices', 'ports', 'agents', 'users', 'packageUnits', 'currencies', 'page', 'quotations', 'chargesData'));
    }

    public function store(StoreAirExportRequest $request)
    {
        try {
            $shipment = $this->airExportService->store($request->validated());

            return redirect()->route('air-export.edit', $shipment->id)
                ->with('success', 'Air Export Shipment created successfully.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Air Export Store - Database Error:', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            // Handle duplicate entry errors
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage = 'This record already exists. ';
                
                if (strpos($e->getMessage(), 'file_no') !== false) {
                    $errorMessage .= 'File No "' . ($request->file_no ?? '') . '" is already used.';
                } elseif (strpos($e->getMessage(), 'mawb_no') !== false) {
                    $errorMessage .= 'MAWB No "' . ($request->mawb_no ?? '') . '" is already used.';
                } elseif (strpos($e->getMessage(), 'hawb_no') !== false) {
                    $errorMessage .= 'One or more HAWB numbers are already used.';
                } else {
                    $errorMessage .= 'Please check your entries and try again.';
                }
                
                return back()->withInput()->with('error', $errorMessage);
            }
            
            return back()->withInput()->with('error', 'Unable to save the shipment. Please check your data and try again.');
            
        } catch (\Exception $e) {
            \Log::error('Air Export Store - General Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->with('error', 'An unexpected error occurred. Please try again or contact support if the problem persists.');
        }
    }

    public function edit(AirExport $airExport)
    {
        $airExport->load(['hbls.customer', 'hbls.shipper', 'hbls.consignee', 'charges.currency', 'documents']);
        
        $offices = Office::where('is_active', true)->get();
        $ports = Port::all();
        $agents = TradePartner::all();
        $users = \App\Models\User::all();
        $packageUnits = \App\Models\PackageUnit::all();
        $currencies = Currency::all();
        $quotations = \App\Models\Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])->latest()->get();
        
        $chargesData = $airExport->charges->isNotEmpty()
            ? $airExport->charges->map(fn($c) => [
                'id' => $c->id,
                'selected' => false,
                'charge_code' => $c->charge_code,
                'charge_name' => $c->charge_name,
                'currency' => $c->currency->code ?? 'USD',
                'currency_id' => $c->currency_id,
                'rate' => $c->rate,
                'qty' => $c->qty,
                'amount' => $c->amount,
                'total_amount' => $c->total_amount ?? $c->amount,
                'pc' => $c->pc,
                'pr' => $c->type === 'AP' || $c->type === 'origin_cost' ? 'Pay' : 'Rec',
                'type' => $c->type,
                'vendor_id' => $c->vendor_id,
                'bill_to_id' => $c->bill_to_id,
                'invoice_no' => $c->invoice_no ?? '',
                'remark' => $c->remark ?? '',
                'created_at' => $c->created_at ? $c->created_at->format('m/d/Y') : '',
            ])
            : collect();
        
        return view('air-export.create', compact('airExport', 'offices', 'ports', 'agents', 'users', 'packageUnits', 'currencies', 'quotations', 'chargesData'));
    }

    public function update(UpdateAirExportRequest $request, AirExport $airExport)
    {
        $this->airExportService->update($airExport, $request->validated());

        return back()->with('success', 'Air Export Shipment updated successfully.');
    }

    public function mblList(Request $request)
    {
        $query = AirExport::with([
            'office', 'operator', 'carrier',
            'depPort', 'dstPort',
            'overseaAgent', 'shipper', 'dmCustomer',
            'hbls',
        ]);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mawb_no', 'like', "%{$search}%")
                  ->orWhere('flight_no', 'like', "%{$search}%")
                  ->orWhereHas('shipper', fn($v) => $v->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('overseaAgent', fn($v) => $v->where('name', 'like', "%{$search}%"));
            });
        }

        // Filters
        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }

        if ($request->filled('op_id')) {
            $query->where('op_id', $request->op_id);
        }

        if ($request->filled('carrier_id')) {
            $query->where('carrier_id', $request->carrier_id);
        }

        // Per-column filters from filter row
        $filterable = [
            'filter_file_no' => 'file_no',
            'filter_mawb_no' => 'mawb_no',
            'filter_etd'     => 'etd',
            'filter_eta'     => 'eta',
            'filter_shipper' => null,
            'filter_customer' => null,
            'filter_dep'     => null,
            'filter_dst'     => null,
        ];
        foreach ($filterable as $param => $column) {
            if ($val = $request->input($param)) {
                match ($param) {
                    'filter_file_no'   => $query->where('file_no', 'like', "%{$val}%"),
                    'filter_mawb_no'   => $query->where('mawb_no', 'like', "%{$val}%"),
                    'filter_etd'       => $query->where('etd', 'like', "%{$val}%"),
                    'filter_eta'       => $query->where('eta', 'like', "%{$val}%"),
                    'filter_shipper'   => $query->whereHas('shipper', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_customer'  => $query->whereHas('dmCustomer', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_dep'       => $query->whereHas('depPort', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_dst'       => $query->whereHas('dstPort', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    default => null,
                };
            }
        }

        if ($request->filled('dep_port_id')) {
            $query->where('dep_port_id', $request->dep_port_id);
        }

        if ($request->filled('dst_port_id')) {
            $query->where('dst_port_id', $request->dst_port_id);
        }

        if ($request->filled('etd_from')) {
            $query->where('etd', '>=', $request->etd_from);
        }

        if ($request->filled('etd_to')) {
            $query->where('etd', '<=', $request->etd_to);
        }

        // Sort (whitelisted)
        $sortField = $request->get('sort', 'created_at');
        $sortDir   = $request->get('dir', 'desc');
        $allowedSorts = ['file_no', 'mawb_no', 'etd', 'eta', 'created_at', 'post_date', 'flight_no'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $shipments = $query->orderBy($sortField, $sortDir)->paginate(20)->withQueryString();

        $offices = Office::where('is_active', true)->get();
        $operators = \App\Models\User::orderBy('name')->get();
        $carriers = TradePartner::whereNotNull('name')->where(function($q) { $q->where('type', 'carrier')->orWhereNull('type'); })->orderBy('name')->get();
        $ports = \App\Models\Port::all();
        $users = \App\Models\User::all();

        return view('air-export.mbl-list', compact('shipments', 'users', 'offices', 'operators', 'carriers', 'ports'));
    }

    public function hblList(Request $request)
    {
        $query = \App\Models\AirExportHbl::with([
            'airExport.office', 'airExport.depPort', 'airExport.dstPort',
            'airExport.overseaAgent',
            'customer', 'shipper', 'consignee', 'notifyParty',
            'salesPerson', 'packageUnit', 'op'
        ]);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('hawb_no', 'like', "%{$search}%")
                  ->orWhereHas('airExport', fn($sq) => $sq->where('file_no', 'like', "%{$search}%"))
                  ->orWhereHas('customer', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('consignee', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('shipper', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        // Per-column filters
        $allowedFilters = ['filter_hawb', 'filter_file_no', 'filter_customer',
                          'filter_consignee', 'filter_shipper', 'filter_sales',
                          'filter_dep', 'filter_dst'];
        foreach ($allowedFilters as $f) {
            if ($val = $request->input($f)) {
                match ($f) {
                    'filter_hawb'      => $query->where('hawb_no', 'like', "%{$val}%"),
                    'filter_file_no'   => $query->whereHas('airExport', fn($q) => $q->where('file_no', 'like', "%{$val}%")),
                    'filter_customer'  => $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_consignee' => $query->whereHas('consignee', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_shipper'   => $query->whereHas('shipper', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_sales'     => $query->whereHas('salesPerson', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_dep'       => $query->whereHas('airExport.depPort', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_dst'       => $query->whereHas('airExport.dstPort', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    default => null,
                };
            }
        }

        // Sort (whitelisted)
        $sortable = ['created_at', 'hawb_no', 'gross_weight', 'chargeable_weight'];
        $sortField = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'created_at';
        $sortDir   = $request->input('dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDir);

        $hbls  = $query->paginate($request->input('per_page', 20))->withQueryString();
        $users = \App\Models\User::all();

        return view('air-export.hbl-list', compact('hbls', 'users'));
    }

    public function destroy(AirExport $airExport)
    {
        $airExport->delete();
        return redirect()->route('air-export.index')->with('success', 'Shipment deleted.');
    }

    public function exportCsv(Request $request)
    {
        $query = AirExport::with(['office', 'operator', 'carrier', 'depPort', 'dstPort', 'hbls', 'shipper']);

        // Apply same filters as mblList()
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mawb_no', 'like', "%{$search}%")
                  ->orWhere('flight_no', 'like', "%{$search}%");
            });
        }
        foreach (['office_id', 'op_id', 'carrier_id', 'dep_port_id', 'dst_port_id'] as $f) {
            if ($request->filled($f)) $query->where($f, $request->$f);
        }
        if ($request->filled('etd_from')) $query->where('etd', '>=', $request->etd_from);
        if ($request->filled('etd_to')) $query->where('etd', '<=', $request->etd_to);

        // Per-column filters from filter row
        foreach (['filter_file_no', 'filter_mawb_no', 'filter_office', 'filter_shipper', 'filter_etd', 'filter_eta', 'filter_dep', 'filter_dst', 'filter_oa', 'filter_customer'] as $param) {
            if ($val = $request->input($param)) {
                match ($param) {
                    'filter_file_no'   => $query->where('file_no', 'like', "%{$val}%"),
                    'filter_mawb_no'   => $query->where('mawb_no', 'like', "%{$val}%"),
                    'filter_office'    => $query->whereHas('office', fn($q) => $q->where('code', 'like', "%{$val}%")),
                    'filter_shipper'   => $query->whereHas('shipper', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_etd'       => $query->where('etd', 'like', "%{$val}%"),
                    'filter_eta'       => $query->where('eta', 'like', "%{$val}%"),
                    'filter_dep'       => $query->whereHas('depPort', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_dst'       => $query->whereHas('dstPort', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_oa'        => $query->whereHas('overseaAgent', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_customer'  => $query->whereHas('dmCustomer', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    default => null,
                };
            }
        }

        $shipments = $query->latest()->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="air-export-mbls.csv"',
        ];
        $callback = function () use ($shipments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['File No', 'MAWB No', 'Carrier', 'ETD', 'ETA', 'Departure', 'Destination', 'Shipper', 'Flight No', 'GW (KG)', 'CW (KG)', 'Status', 'HBLs']);
            foreach ($shipments as $s) {
                fputcsv($file, [
                    $s->file_no, $s->mawb_no, $s->carrier->name ?? '',
                    $s->etd ? $s->etd->format('Y-m-d') : '',
                    $s->eta ? $s->eta->format('Y-m-d') : '',
                    $s->depPort->name ?? '', $s->dstPort->name ?? '',
                    $s->shipper->name ?? '', $s->flight_no ?? '',
                    number_format($s->gross_weight ?? 0, 2),
                    number_format($s->chargeable_weight ?? 0, 2),
                    $s->is_blocked ? 'Blocked' : 'Open',
                    $s->hbls->count(),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_exports,id']);
        AirExport::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => count($request->ids) . ' shipment(s) deleted.']);
    }

    public function updateColor(Request $request, $id)
    {
        $shipment = AirExport::findOrFail($id);
        $request->validate(['color' => 'nullable|string|max:20']);
        $shipment->update(['color' => $request->color]);
        return response()->json(['success' => true]);
    }

    public function hblUpdateColor(Request $request, $id)
    {
        $hbl = \App\Models\AirExportHbl::findOrFail($id);
        $request->validate(['color' => 'nullable|string|max:20']);
        $hbl->update(['color' => $request->color]);
        return response()->json(['success' => true]);
    }

    public function bulkBlock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_exports,id']);
        AirExport::whereIn('id', $request->ids)->update(['is_blocked' => true]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' shipment(s) blocked.']);
    }

    public function bulkUnblock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_exports,id']);
        AirExport::whereIn('id', $request->ids)->update(['is_blocked' => false]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' shipment(s) unblocked.']);
    }

    public function bulkChangeOp(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_exports,id', 'op_id' => 'required|exists:users,id']);
        AirExport::whereIn('id', $data['ids'])->update(['op_id' => $data['op_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' shipment(s) OP changed.']);
    }

    public function bulkChangeSales(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_exports,id', 'sales_person_id' => 'required|exists:users,id']);
        AirExport::whereIn('id', $data['ids'])->update(['dm_sales_person_id' => $data['sales_person_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' shipment(s) sales changed.']);
    }

    // ==================== HBL BULK OPERATIONS ====================

    public function hblBulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_export_hbls,id']);
        \App\Models\AirExportHbl::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => count($request->ids) . ' HBL(s) deleted.']);
    }

    public function hblBulkChangeSales(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_export_hbls,id', 'sales_person_id' => 'required|exists:users,id']);
        \App\Models\AirExportHbl::whereIn('id', $data['ids'])->update(['sales_person_id' => $data['sales_person_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' HBL(s) sales changed.']);
    }

    public function hblBulkBlock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_export_hbls,id']);
        \App\Models\AirExportHbl::whereIn('id', $request->ids)->update(['is_blocked' => true]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' HBL(s) blocked.']);
    }

    public function hblBulkUnblock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_export_hbls,id']);
        \App\Models\AirExportHbl::whereIn('id', $request->ids)->update(['is_blocked' => false]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' HBL(s) unblocked.']);
    }

    public function hblBulkChangeOp(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_export_hbls,id', 'op_id' => 'required|exists:users,id']);
        \App\Models\AirExportHbl::whereIn('id', $data['ids'])->update(['op_id' => $data['op_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' HBL(s) OP changed.']);
    }

    public function hblExportCsv(Request $request)
    {
        $query = \App\Models\AirExportHbl::with(['airExport', 'customer', 'shipper', 'consignee', 'salesPerson']);

        // Apply same filters as hblList()
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('hawb_no', 'like', "%{$search}%")
                  ->orWhereHas('airExport', fn($sq) => $sq->where('file_no', 'like', "%{$search}%"))
                  ->orWhereHas('customer', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        foreach (['filter_hawb', 'filter_file_no', 'filter_customer', 'filter_shipper'] as $f) {
            if ($val = $request->input($f)) {
                match ($f) {
                    'filter_hawb'     => $query->where('hawb_no', 'like', "%{$val}%"),
                    'filter_file_no'  => $query->whereHas('airExport', fn($q) => $q->where('file_no', 'like', "%{$val}%")),
                    'filter_customer' => $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    'filter_shipper'  => $query->whereHas('shipper', fn($q) => $q->where('name', 'like', "%{$val}%")),
                    default => null,
                };
            }
        }

        $hbls = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="air-export-hbls.csv"',
        ];
        $callback = function () use ($hbls) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['HAWB No', 'File No', 'Customer', 'Shipper', 'Consignee', 'GW (KG)', 'CW (KG)', 'Sales Person', 'Created']);
            foreach ($hbls as $h) {
                fputcsv($file, [
                    $h->hawb_no,
                    $h->airExport->file_no ?? '',
                    $h->customer->name ?? '',
                    $h->shipper->name ?? '',
                    $h->consignee->name ?? '',
                    $h->gross_weight ? number_format($h->gross_weight, 2) : '0.00',
                    $h->chargeable_weight ? number_format($h->chargeable_weight, 2) : '0.00',
                    $h->salesPerson->name ?? '',
                    $h->created_at ? $h->created_at->format('Y-m-d') : '',
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // ==================== CHARGES AJAX ENDPOINTS ====================

    public function addCharge(Request $request, AirExport $airExport)
    {
        $charge = $this->airExportService->createCharge($airExport, $request->all());
        $charge->load('currency');
        return response()->json(['success' => true, 'charge' => $charge]);
    }

    public function updateCharge(Request $request, $chargeId)
    {
        $charge = Charge::findOrFail($chargeId);
        $this->airExportService->updateCharge($charge, $request->all());
        $charge->load('currency');
        return response()->json(['success' => true, 'charge' => $charge]);
    }

    public function deleteCharge($chargeId)
    {
        $charge = Charge::findOrFail($chargeId);
        $charge->delete();
        return response()->json(['success' => true]);
    }

    public function deleteAllCharges(AirExport $airExport)
    {
        $airExport->charges()->delete();
        return response()->json(['success' => true]);
    }

    public function getCharges(AirExport $airExport)
    {
        $charges = $airExport->charges()->with('currency')->latest()->get();
        return response()->json($charges);
    }

    // ==================== HISTORY ====================

    public function getHistory(AirExport $airExport)
    {
        $logs = $airExport->statusLogs()->with('user')->latest()->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'action' => $log->action ?? $log->status_name,
                'details' => $log->details,
                'user' => $log->user ? $log->user->name : 'System',
                'user_initials' => $log->user ? substr($log->user->name, 0, 1) : 'S',
                'created_at' => $log->created_at ? $log->created_at->format('m-d-Y') : '',
                'created_time' => $log->created_at ? $log->created_at->format('H:i') : '',
            ];
        });

        return response()->json($logs);
    }

    public function saveInternalMessage(Request $request, AirExport $airExport)
    {
        $request->validate(['message' => 'nullable|string']);
        
        $airExport->internal_remark = $request->message;
        $airExport->save();

        return response()->json(['success' => true]);
    }

    // ==================== MEMOS ====================

    public function getMemos(AirExport $airExport)
    {
        $memos = $airExport->memos()->latest()->get();
        return response()->json($memos);
    }

    public function addMemo(Request $request, AirExport $airExport)
    {
        $data = $request->validate([
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        $memo = $airExport->memos()->create([
            'subject' => $data['subject'] ?? '',
            'body' => $data['body'] ?? '',
            'user_id' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'memo' => $memo]);
    }

    public function updateMemo(Request $request, $memoId)
    {
        $memo = \App\Models\AirExportMemo::findOrFail($memoId);
        $data = $request->validate([
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);
        $memo->update($data);
        return response()->json(['success' => true, 'memo' => $memo]);
    }

    public function deleteMemo($memoId)
    {
        $memo = \App\Models\AirExportMemo::findOrFail($memoId);
        $memo->delete();
        return response()->json(['success' => true]);
    }

    // ==================== EMAIL CHARGE ====================

    public function emailCharge(Request $request, AirExport $airExport)
    {
        $request->validate(['charge_id' => 'required|exists:charges,id']);
        \Illuminate\Support\Facades\Log::info('Email charge requested', [
            'shipment_id' => $airExport->id,
            'charge_id' => $request->charge_id,
        ]);
        return response()->json(['success' => true, 'message' => 'Email functionality will be implemented.']);
    }
}
