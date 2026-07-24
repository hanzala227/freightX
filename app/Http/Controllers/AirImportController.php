<?php

namespace App\Http\Controllers;

use App\Models\AirImport;
use App\Models\AirImportHbl;
use App\Models\AirImportContainer;
use App\Models\Office;
use App\Models\Port;
use App\Models\TradePartner;
use App\Models\ContainerType;
use App\Models\Currency;
use App\Models\ShipmentStatusLog;
use App\Services\AirImportService;
use App\Http\Requests\StoreAirImportRequest;
use App\Http\Requests\UpdateAirImportRequest;
use Illuminate\Http\Request;

class AirImportController extends Controller
{
    protected $airImportService;

    public function __construct(AirImportService $service)
    {
        $this->airImportService = $service;
    }

    public function index()
    {
        $shipments = AirImport::with([
            'office', 'operator', 'carrier', 
            'depPort', 'dstPort',
            'forwardingAgent', 'overseaAgent',
            'acctCarrier', 'packageUnit',
            'hbls'
        ])
            ->latest()
            ->paginate(20);

        $users = \App\Models\User::all();

        return view('air-import.list', compact('shipments', 'users'));
    }

    public function create(Request $request)
    {
        $offices = Office::where('is_active', true)->get();
        $ports = Port::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        $packageUnits = \App\Models\PackageUnit::all();
        $containerTypes = ContainerType::all();
        $incoterms = \App\Models\Incoterm::all();
        $serviceTerms = \App\Models\ServiceTerm::all();
        $currencies = Currency::all();
        
        // Separate trade partners by type for easier use in dropdowns
        $allAgents = TradePartner::orderBy('name')->get();
        $carriers = TradePartner::whereIn('type', ['CR', 'CARRIER'])->orderBy('name')->get();
        $customers = TradePartner::whereIn('type', ['CS', 'CUSTOMER', 'CLIENT'])->orderBy('name')->get();
        $agents = TradePartner::whereIn('type', ['AG', 'AGENT', 'OA'])->orderBy('name')->get();
        $truckers = TradePartner::whereIn('type', ['TR', 'TRUCKER'])->orderBy('name')->get();
        $brokers = TradePartner::whereIn('type', ['CB', 'BROKER'])->orderBy('name')->get();
        $forwarders = TradePartner::whereIn('type', ['FW', 'FORWARDER'])->orderBy('name')->get();
        $coloaders = TradePartner::whereIn('type', ['CL', 'COLOADER'])->orderBy('name')->get();
        
        // Fallback to all if type-specific lists are empty
        if ($carriers->isEmpty()) $carriers = $allAgents;
        if ($customers->isEmpty()) $customers = $allAgents;
        if ($agents->isEmpty()) $agents = $allAgents;
        if ($truckers->isEmpty()) $truckers = $allAgents;
        if ($brokers->isEmpty()) $brokers = $allAgents;
        if ($forwarders->isEmpty()) $forwarders = $allAgents;
        if ($coloaders->isEmpty()) $coloaders = $allAgents;
        
        $page = $request->route()->getName();
        $quotations = \App\Models\Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])->latest()->get();
        
        $chargesData = collect();
        
        return view('air-import.index', compact(
            'offices', 'ports', 'users', 'packageUnits', 
            'containerTypes', 'incoterms', 'serviceTerms', 'currencies',
            'allAgents', 'carriers', 'customers', 'agents', 'truckers', 'brokers', 'forwarders', 'coloaders',
            'page', 'quotations', 'chargesData'
        ));
    }

    public function store(StoreAirImportRequest $request)
    {
        $shipment = $this->airImportService->store($request->validated());

        return redirect()->route('air-import.edit', $shipment->id)
            ->with('success', 'Air Import Shipment created successfully.');
    }

    public function edit(AirImport $airImport)
    {
        $airImport->load([
            'hbls.customer', 'hbls.shipper', 'hbls.consignee', 
            'charges.currency', 'documents', 'containers',
            'statusLogs.user', 'office', 'carrier', 'overseaAgent',
            'depPort', 'dstPort', 'operator', 'packageUnit',
            'incoterm', 'svcTermFrom', 'svcTermTo', 'referredBy'
        ]);
        
        $offices = Office::where('is_active', true)->get();
        $ports = Port::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        $packageUnits = \App\Models\PackageUnit::all();
        $containerTypes = ContainerType::all();
        $incoterms = \App\Models\Incoterm::all();
        $serviceTerms = \App\Models\ServiceTerm::all();
        $currencies = Currency::all();
        
        // Separate trade partners by type
        $allAgents = TradePartner::orderBy('name')->get();
        $carriers = TradePartner::whereIn('type', ['CR', 'CARRIER'])->orderBy('name')->get();
        $customers = TradePartner::whereIn('type', ['CS', 'CUSTOMER', 'CLIENT'])->orderBy('name')->get();
        $agents = TradePartner::whereIn('type', ['AG', 'AGENT', 'OA'])->orderBy('name')->get();
        $truckers = TradePartner::whereIn('type', ['TR', 'TRUCKER'])->orderBy('name')->get();
        $brokers = TradePartner::whereIn('type', ['CB', 'BROKER'])->orderBy('name')->get();
        $forwarders = TradePartner::whereIn('type', ['FW', 'FORWARDER'])->orderBy('name')->get();
        $coloaders = TradePartner::whereIn('type', ['CL', 'COLOADER'])->orderBy('name')->get();
        
        // Fallback to all if type-specific lists are empty
        if ($carriers->isEmpty()) $carriers = $allAgents;
        if ($customers->isEmpty()) $customers = $allAgents;
        if ($agents->isEmpty()) $agents = $allAgents;
        if ($truckers->isEmpty()) $truckers = $allAgents;
        if ($brokers->isEmpty()) $brokers = $allAgents;
        if ($forwarders->isEmpty()) $forwarders = $allAgents;
        if ($coloaders->isEmpty()) $coloaders = $allAgents;
        
        $quotations = \App\Models\Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])->latest()->get();
        
        $chargesData = $airImport->charges->isNotEmpty()
            ? $airImport->charges->map(fn($c) => [
                'id' => $c->id,
                'selected' => false,
                'chrg_code' => $c->charge_code,
                'currency' => $c->currency->code ?? 'USD',
                'rate' => $c->rate,
                'qty' => $c->qty,
                'qty_type' => $c->unit ?? 'B/L',
                'pr' => $c->type === 'AP' ? 'Pay' : 'Rec',
                'ppc' => $c->pc === 'PREPAID' ? 'Prepaid' : 'Colle',
                'eq_bl_no' => $c->remark ?? '',
                'vat' => $c->tax_percent ?? 0,
                'roe' => 1.0,
                'inv_no' => $c->invoice_no ?? '',
            ])
            : collect();
        
        return view('air-import.index', compact(
            'airImport', 'offices', 'ports', 'users', 'packageUnits',
            'containerTypes', 'incoterms', 'serviceTerms', 'currencies',
            'allAgents', 'carriers', 'customers', 'agents', 'truckers', 'brokers', 'forwarders', 'coloaders',
            'quotations', 'chargesData'
        ));
    }

    public function update(UpdateAirImportRequest $request, AirImport $airImport)
    {
        $this->airImportService->update($airImport, $request->validated());

        return back()->with('success', 'Air Import Shipment updated successfully.');
    }

    public function mblList()
    {
        $shipments = AirImport::with(['office', 'operator', 'carrier'])
            ->latest()
            ->paginate(20);
        $users = \App\Models\User::all();
        return view('air-import.mbl-list', compact('shipments', 'users'));
    }

    public function hblList(Request $request)
    {
        $query = AirImportHbl::with([
            'airImport.depPort', 'airImport.dstPort', 'airImport.office', 'airImport.carrier',
            'customer', 'shipper', 'consignee', 'salesPerson', 'op',
        ]);

        // Global search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('hawb_no', 'like', "%{$search}%")
                  ->orWhereHas('airImport', fn($aq) => $aq->where('file_no', 'like', "%{$search}%")
                      ->orWhere('mawb_no', 'like', "%{$search}%"))
                  ->orWhereHas('shipper', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('consignee', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('customer', fn($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }

        // Per-column filters
        if ($request->filled('filter_hawb')) {
            $query->where('hawb_no', 'like', "%{$request->filter_hawb}%");
        }
        if ($request->filled('filter_file_no')) {
            $query->whereHas('airImport', fn($aq) => $aq->where('file_no', 'like', "%{$request->filter_file_no}%"));
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('customer', fn($tq) => $tq->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_shipper')) {
            $query->whereHas('shipper', fn($tq) => $tq->where('name', 'like', "%{$request->filter_shipper}%"));
        }
        if ($request->filled('filter_consignee')) {
            $query->whereHas('consignee', fn($tq) => $tq->where('name', 'like', "%{$request->filter_consignee}%"));
        }
        if ($request->filled('filter_dep')) {
            $query->whereHas('airImport.depPort', fn($pq) => $pq->where('name', 'like', "%{$request->filter_dep}%"));
        }
        if ($request->filled('filter_dst')) {
            $query->whereHas('airImport.dstPort', fn($pq) => $pq->where('name', 'like', "%{$request->filter_dst}%"));
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['hawb_no', 'pkg_qty', 'gross_weight', 'chargeable_weight', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $hbls = $query->orderBy($sortField, $sortDir)->paginate(20)->withQueryString();
        $users = \App\Models\User::orderBy('name')->get();

        return view('air-import.hbl-list', compact('hbls', 'users'));
    }

    public function destroy(AirImport $airImport)
    {
        $airImport->delete();
        return redirect()->route('air-import.index')->with('success', 'Shipment deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_imports,id']);
        AirImport::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => count($request->ids) . ' shipment(s) deleted.']);
    }

    public function updateColor(Request $request, $id)
    {
        $shipment = AirImport::findOrFail($id);
        $request->validate(['color' => 'nullable|string|max:20']);
        $shipment->update(['color' => $request->color]);
        return response()->json(['success' => true]);
    }

    public function bulkChangeOp(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_imports,id', 'op_id' => 'required|exists:users,id']);
        AirImport::whereIn('id', $data['ids'])->update(['op_id' => $data['op_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' shipment(s) OP changed.']);
    }

    public function bulkBlock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_imports,id']);
        AirImport::whereIn('id', $request->ids)->update(['is_blocked' => true]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' shipment(s) blocked.']);
    }

    public function bulkUnblock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_imports,id']);
        AirImport::whereIn('id', $request->ids)->update(['is_blocked' => false]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' shipment(s) unblocked.']);
    }

    public function bulkChangeSales(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_imports,id', 'sales_person_id' => 'required|exists:users,id']);
        AirImport::whereIn('id', $data['ids'])->update(['dm_sales_person_id' => $data['sales_person_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' shipment(s) sales changed.']);
    }

    // ==================== HBL BULK OPERATIONS ====================

    public function hblBulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_import_hbls,id']);
        AirImportHbl::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => count($request->ids) . ' HBL(s) deleted.']);
    }

    public function hblBulkBlock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_import_hbls,id']);
        AirImportHbl::whereIn('id', $request->ids)->update(['is_blocked' => true]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' HBL(s) blocked.']);
    }

    public function hblBulkUnblock(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_import_hbls,id']);
        AirImportHbl::whereIn('id', $request->ids)->update(['is_blocked' => false]);
        return response()->json(['success' => true, 'message' => count($request->ids) . ' HBL(s) unblocked.']);
    }

    public function hblBulkChangeOp(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_import_hbls,id', 'op_id' => 'required|exists:users,id']);
        AirImportHbl::whereIn('id', $data['ids'])->update(['op_id' => $data['op_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' HBL(s) OP changed.']);
    }

    public function hblBulkChangeSales(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:air_import_hbls,id', 'sales_person_id' => 'required|exists:users,id']);
        AirImportHbl::whereIn('id', $data['ids'])->update(['sales_person_id' => $data['sales_person_id']]);
        return response()->json(['success' => true, 'message' => count($data['ids']) . ' HBL(s) sales changed.']);
    }

    public function exportCsv()
    {
        $shipments = AirImport::with(['office', 'operator', 'carrier', 'depPort', 'dstPort', 'hbls'])->latest()->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="air-import-shipments.csv"',
        ];
        $callback = function () use ($shipments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['File No', 'MAWB No', 'Carrier', 'ETD', 'ETA', 'Departure', 'Destination', 'Oversea Agent', 'Flight No']);
            foreach ($shipments as $s) {
                fputcsv($file, [
                    $s->file_no, $s->mawb_no, $s->carrier->name ?? '',
                    $s->etd ? $s->etd->format('Y-m-d H:i') : '',
                    $s->eta ? $s->eta->format('Y-m-d H:i') : '',
                    $s->depPort->name ?? '', $s->dstPort->name ?? '',
                    $s->overseaAgent->name ?? '', $s->flight_no ?? '',
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function hblExportCsv(Request $request)
    {
        $query = AirImportHbl::with([
            'airImport.depPort', 'airImport.dstPort', 'airImport.carrier',
            'customer', 'shipper', 'consignee', 'salesPerson', 'op',
        ]);

        // Apply same filters as hblList
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('hawb_no', 'like', "%{$search}%")
                  ->orWhereHas('airImport', fn($aq) => $aq->where('file_no', 'like', "%{$search}%")
                      ->orWhere('mawb_no', 'like', "%{$search}%"))
                  ->orWhereHas('shipper', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('consignee', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('customer', fn($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('filter_hawb')) {
            $query->where('hawb_no', 'like', "%{$request->filter_hawb}%");
        }
        if ($request->filled('filter_file_no')) {
            $query->whereHas('airImport', fn($aq) => $aq->where('file_no', 'like', "%{$request->filter_file_no}%"));
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('customer', fn($tq) => $tq->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_shipper')) {
            $query->whereHas('shipper', fn($tq) => $tq->where('name', 'like', "%{$request->filter_shipper}%"));
        }
        if ($request->filled('filter_consignee')) {
            $query->whereHas('consignee', fn($tq) => $tq->where('name', 'like', "%{$request->filter_consignee}%"));
        }
        if ($request->filled('filter_dep')) {
            $query->whereHas('airImport.depPort', fn($pq) => $pq->where('name', 'like', "%{$request->filter_dep}%"));
        }
        if ($request->filled('filter_dst')) {
            $query->whereHas('airImport.dstPort', fn($pq) => $pq->where('name', 'like', "%{$request->filter_dst}%"));
        }

        $hbls = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="air-import-hbls-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($hbls) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'HAWB No', 'File No', 'MAWB No', 'Customer', 'Shipper', 'Consignee',
                'Package Qty', 'G. Weight (KG)', 'C. Weight (KG)',
                'ETD', 'ETA', 'Departure', 'Destination',
                'Sales', 'OP', 'Created At'
            ]);
            foreach ($hbls as $h) {
                fputcsv($file, [
                    $h->hawb_no,
                    $h->airImport->file_no ?? '--',
                    $h->airImport->mawb_no ?? '--',
                    $h->customer->name ?? '--',
                    $h->shipper->name ?? '--',
                    $h->consignee->name ?? '--',
                    $h->pkg_qty ?? '--',
                    $h->gross_weight ? number_format($h->gross_weight, 2) : '--',
                    $h->chargeable_weight ? number_format($h->chargeable_weight, 2) : '--',
                    $h->airImport->etd ? $h->airImport->etd->format('m-d-Y') : '--',
                    $h->airImport->eta ? $h->airImport->eta->format('m-d-Y') : '--',
                    $h->airImport->depPort->name ?? '--',
                    $h->airImport->dstPort->name ?? '--',
                    $h->salesPerson->name ?? '--',
                    $h->op->name ?? '--',
                    $h->created_at ? $h->created_at->format('m-d-Y H:i') : '--',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==================== CHARGES AJAX ENDPOINTS ====================

    public function addCharge(Request $request, AirImport $airImport)
    {
        $charge = $this->airImportService->createCharge($airImport, $request->all());
        $charge->load('currency');
        return response()->json(['success' => true, 'charge' => $charge]);
    }

    public function updateCharge(Request $request, $chargeId)
    {
        $charge = \App\Models\Charge::findOrFail($chargeId);
        $this->airImportService->updateCharge($charge, $request->all());
        $charge->load('currency');
        return response()->json(['success' => true, 'charge' => $charge]);
    }

    public function deleteCharge($chargeId)
    {
        $charge = \App\Models\Charge::findOrFail($chargeId);
        $charge->delete();
        return response()->json(['success' => true]);
    }

    public function deleteAllCharges(AirImport $airImport)
    {
        $airImport->charges()->delete();
        return response()->json(['success' => true]);
    }

    public function getCharges(AirImport $airImport)
    {
        $charges = $airImport->charges()->with('currency')->latest()->get();
        return response()->json($charges);
    }

    // ==================== CONTAINERS AJAX ENDPOINTS ====================

    public function addContainer(Request $request, AirImport $airImport)
    {
        $container = $airImport->containers()->create($request->validate([
            'container_no' => 'nullable|string',
            'pp_ctf' => 'nullable|string',
            'container_type' => 'nullable|string',
            'seal_no' => 'nullable|string',
            'seal_no2' => 'nullable|string',
            'lfd' => 'nullable|date',
            'fdd' => 'nullable|date',
            'pkg_qty' => 'nullable|numeric',
            'weight_kg' => 'nullable|numeric',
            'measure_cbm' => 'nullable|numeric',
        ]));
        return response()->json(['success' => true, 'container' => $container]);
    }

    public function updateContainer(Request $request, AirImportContainer $container)
    {
        $container->update($request->all());
        return response()->json(['success' => true, 'container' => $container]);
    }

    public function deleteContainer(AirImportContainer $container)
    {
        $container->delete();
        return response()->json(['success' => true]);
    }

    // ==================== FILING AJAX ENDPOINTS ====================

    public function updateFiling(Request $request, AirImport $airImport)
    {
        $data = $request->validate([
            'shipper_id' => 'nullable|exists:trade_partners,id',
            'consignee_id' => 'nullable|exists:trade_partners,id',
            'bill_to_id' => 'nullable|exists:trade_partners,id',
            'oversea_agent_id' => 'nullable|exists:trade_partners,id',
            'notify_id' => 'nullable|exists:trade_partners,id',
            'trucker_id' => 'nullable|exists:trade_partners,id',
            'pod_eta' => 'nullable|date',
            'ship_mode' => 'nullable|string',
            'go_date' => 'nullable|date',
            'sub_bl_no' => 'nullable|string',
            'final_destination_id' => 'nullable|exists:ports,id',
            'delivery_location_id' => 'nullable|exists:trade_partners,id',
            'final_eta' => 'nullable|date',
            'last_free_day' => 'nullable|date',
            'storage_start_date' => 'nullable|date',
            'internal_remark' => 'nullable|string',
            'cy_cfs_loc' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'ams_no' => 'nullable|string',
            'isf_no' => 'nullable|string',
            'isf_matched_date' => 'nullable|date',
            'isf_3rd_party' => 'nullable|boolean',
            'sales_type' => 'nullable|string',
            'c_released_date' => 'nullable|date',
            'entry_no' => 'nullable|string',
            'ror' => 'nullable|boolean',
            'released_by_id' => 'nullable|exists:users,id',
            'do_sent' => 'nullable|boolean',
            'do_sent_date' => 'nullable|date',
            'entry_doc_sent_date' => 'nullable|date',
            'hold' => 'nullable|boolean',
            'door_delivered_date' => 'nullable|date',
            'class_of_entry' => 'nullable|string',
            'cargo_released_to' => 'nullable|string',
            'ship_type' => 'nullable|string',
            'freight_term' => 'nullable|string',
            'incoterm_id' => 'nullable|string',
            'service_term_from' => 'nullable|string',
            'service_term_to' => 'nullable|string',
            'cargo_type' => 'nullable|string',
            // Direct Master fields
            'is_direct_master' => 'nullable|boolean',
            'dm_customer_id' => 'nullable|exists:trade_partners,id',
            'dm_shipper_id' => 'nullable|exists:trade_partners,id',
            'dm_consignee_id' => 'nullable|exists:trade_partners,id',
            'dm_notify_id' => 'nullable|exists:trade_partners,id',
            'dm_bill_to_id' => 'nullable|exists:trade_partners,id',
            'dm_sales_person_id' => 'nullable|exists:users,id',
        ]);

        $airImport->update($data);

        ShipmentStatusLog::create([
            'shipment_type' => AirImport::class,
            'shipment_id' => $airImport->id,
            'status_code' => 'FILING_UPDATED',
            'status_name' => 'Filing Updated',
            'details' => 'Filing details updated.',
            'user_id' => auth()->id(),
            'event_time' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Filing details updated successfully.']);
    }

    // ==================== HISTORY AJAX ENDPOINTS ====================

    public function getHistory(AirImport $airImport)
    {
        $history = $airImport->statusLogs()->with('user')->latest()->get();
        return response()->json($history);
    }

    // ==================== DOCUMENT AJAX ENDPOINTS ====================

    public function uploadDocument(Request $request, AirImport $airImport)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'description' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('shipments/documents', 'public');

        $doc = $airImport->documents()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'uploaded_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'document' => $doc]);
    }

    public function deleteDocument($documentId)
    {
        $doc = \App\Models\Document::findOrFail($documentId);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
        return response()->json(['success' => true]);
    }

    public function downloadDocument($documentId)
    {
        $doc = \App\Models\Document::findOrFail($documentId);
        return \Illuminate\Support\Facades\Storage::disk('public')->download($doc->file_path, $doc->file_name);
    }
}
