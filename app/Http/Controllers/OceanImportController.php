<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\OceanImport;
use App\Models\Office;
use App\Models\Port;
use App\Models\Vessel;
use App\Models\TradePartner;
use App\Models\OceanImportContainer;
use App\Models\OceanImportHbl;
use App\Models\OceanImportCharge;
use App\Models\OceanImportDocument;
use App\Models\OceanImportMemo;
use App\Models\OceanImportHistory;
use App\Models\WarehouseReceipt;
use App\Models\Quotation;
use App\Models\User;
use App\Models\ContainerType;
use App\Models\PackageUnit;
use App\Models\Currency;
use App\Services\OceanImportService;
use App\Http\Requests\StoreOceanImportRequest;
use App\Http\Requests\UpdateOceanImportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OceanImportController extends Controller
{
    use AuthorizesRequests;

    protected $oceanImportService;

    public function __construct(OceanImportService $service)
    {
        $this->oceanImportService = $service;
    }

    public function index(Request $request)
    {
        $query = OceanImport::with([
            'office', 'operator', 'carrier', 'vessel',
            'portOfLoading', 'portOfDischarge',
            'placeOfDelivery', 'finalDestination',
            'dmCustomer', 'dmConsignee', 'overseaAgent',
            'trucker', 'incoterm', 'salesPerson',
            'containers.containerType', 'hbls',
        ]);

        $this->applyFiltersToQuery($query, $request);

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['file_no', 'mbl_no', 'etd', 'eta', 'created_at', 'post_date'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $shipments = $query->orderBy($sortField, $sortDir)->paginate(20)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('ocean-import.partials.list-rows', compact('shipments'))->render(),
                'pagination' => view('vendor.pagination.custom', ['paginator' => $shipments])->render(),
                'first' => $shipments->firstItem() ?? 0,
                'last' => $shipments->lastItem() ?? 0,
                'total' => $shipments->total(),
            ]);
        }

        $offices = Office::where('is_active', true)->get();
        $users = User::all();
        $agents = TradePartner::all();
        $ports = Port::all();

        return view('ocean-import.list', compact('shipments', 'offices', 'users', 'agents', 'ports'));
    }

    private function applyFiltersToQuery($query, Request $request)
    {
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mbl_no', 'like', "%{$search}%")
                  ->orWhere('sub_bl_no', 'like', "%{$search}%")
                  ->orWhere('voyage', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter_file_no')) {
            $query->where('file_no', 'like', "%{$request->filter_file_no}%");
        }
        if ($request->filled('filter_mbl_no')) {
            $query->where('mbl_no', 'like', "%{$request->filter_mbl_no}%");
        }
        if ($request->filled('filter_office')) {
            $query->whereHas('office', fn($q) => $q->where('code', 'like', "%{$request->filter_office}%")->orWhere('name', 'like', "%{$request->filter_office}%"));
        }
        if ($request->filled('filter_consignee')) {
            $query->whereHas('dmConsignee', fn($q) => $q->where('name', 'like', "%{$request->filter_consignee}%"));
        }
        if ($request->filled('filter_etd')) {
            $query->where('etd', 'like', "%{$request->filter_etd}%");
        }
        if ($request->filled('filter_eta')) {
            $query->where('eta', 'like', "%{$request->filter_eta}%");
        }
        if ($request->filled('filter_pol')) {
            $query->whereHas('portOfLoading', fn($q) => $q->where('name', 'like', "%{$request->filter_pol}%"));
        }
        if ($request->filled('filter_pod')) {
            $query->whereHas('portOfDischarge', fn($q) => $q->where('name', 'like', "%{$request->filter_pod}%"));
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('dmCustomer', fn($q) => $q->where('name', 'like', "%{$request->filter_customer}%"));
        }
        if ($request->filled('filter_sales')) {
            $query->whereHas('salesPerson', fn($q) => $q->where('name', 'like', "%{$request->filter_sales}%"));
        }

        if ($request->filled('office_id')) $query->where('office_id', $request->office_id);
        if ($request->filled('op_id')) $query->where('op_id', $request->op_id);
        if ($request->filled('carrier_id')) $query->where('carrier_id', $request->carrier_id);
        if ($request->filled('pol_id')) $query->where('pol_id', $request->pol_id);
        if ($request->filled('pod_id')) $query->where('pod_id', $request->pod_id);
        if ($request->filled('dm_customer_id')) $query->where('dm_customer_id', $request->dm_customer_id);
        if ($request->filled('etd_from')) $query->where('etd', '>=', $request->etd_from);
        if ($request->filled('etd_to')) $query->where('etd', '<=', $request->etd_to);
        if ($request->filled('eta_from')) $query->where('eta', '>=', $request->eta_from);
        if ($request->filled('eta_to')) $query->where('eta', '<=', $request->eta_to);

        return $query;
    }

    public function create(Request $request)
    {
        $offices = Office::where('is_active', true)->get();
        $ports = Port::all();
        $vessels = Vessel::all();
        $agents = TradePartner::all();
        $users = User::all();
        $containerTypes = ContainerType::all();
        $packageUnits = PackageUnit::all();
        $incoterms = \App\Models\Incoterm::all();
        $currencies = Currency::all();
        $serviceTerms = \App\Models\ServiceTerm::all();

        $page = $request->segment(2);
        $quotations = Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])->latest()->get();

        $oceanImport = null;

        if ($request->filled('copy')) {
            $source = OceanImport::with([
                'hbls.customer', 'hbls.shipper', 'hbls.consignee',
                'hbls.containers', 'hbls.commodities', 'hbls.receipts',
                'containers.containerType', 'charges.currency', 'memos',
            ])->find($request->copy);
            if (!$source) {
                return redirect()->route('ocean-import.create')
                    ->with('error', 'Shipment #' . $request->copy . ' not found. Cannot copy.');
            }

            $oceanImport = $source->replicate();
            
            // Generate unique file_no with timestamp + random string to prevent duplicates
            $fileAttempt = 0;
            do {
                $proposedFileNo = 'MOI-' . now()->format('ymdHis') . ($fileAttempt > 0 ? ('-' . rand(100, 999)) : ('-' . strtoupper(substr(uniqid(), -4))));
                $fileAttempt++;
            } while (OceanImport::where('file_no', $proposedFileNo)->exists());
            
            $oceanImport->file_no = $proposedFileNo;
            $oceanImport->mbl_no = null;
            $oceanImport->save();

            foreach ($source->containers as $container) {
                $clonedContainer = $container->replicate();
                $clonedContainer->ocean_import_id = $oceanImport->id;
                
                $cleanContainerNo = preg_replace('/(-Copy(-\d+)?)+$/i', '', $container->container_no ?? '');
                if (!$cleanContainerNo) $cleanContainerNo = 'CNTR';
                
                $containerAttempt = 0;
                do {
                    $uniqueSuffix = '-Copy-' . now()->format('YmdHis') . ($containerAttempt > 0 ? ('-' . rand(100, 999)) : '');
                    $maxBaseLength = 255 - strlen($uniqueSuffix);
                    $proposedContainerNo = (strlen($cleanContainerNo) > $maxBaseLength) ? substr($cleanContainerNo, 0, $maxBaseLength) . $uniqueSuffix : $cleanContainerNo . $uniqueSuffix;
                    $containerAttempt++;
                } while (\App\Models\OceanImportContainer::where('container_no', $proposedContainerNo)->where('ocean_import_id', $oceanImport->id)->exists());
                
                $clonedContainer->container_no = $proposedContainerNo;
                $clonedContainer->save();
            }

            foreach ($source->hbls as $hbl) {
                $clonedHbl = $hbl->replicate();
                $clonedHbl->ocean_import_id = $oceanImport->id;
                
                // Clean base HBL number by stripping any accumulated "- Copy ..." suffixes
                $cleanBaseHblNo = preg_replace('/(\s*-\s*Copy(\s+\d+)?)+$/i', '', $hbl->hbl_no ?? '');
                if (!$cleanBaseHblNo) {
                    $cleanBaseHblNo = 'HBL-' . rand(1000, 9999);
                }

                // Loop check to guarantee absolute uniqueness in DB without constraint violation
                $hblAttempt = 0;
                do {
                    $uniqueSuffix = ' - Copy ' . now()->format('YmdHis') . ($hblAttempt > 0 ? ('-' . rand(100, 999)) : '');
                    $maxBaseLength = 255 - strlen($uniqueSuffix);
                    $proposedHblNo = (strlen($cleanBaseHblNo) > $maxBaseLength) ? substr($cleanBaseHblNo, 0, $maxBaseLength) . $uniqueSuffix : $cleanBaseHblNo . $uniqueSuffix;
                    $hblAttempt++;
                } while (OceanImportHbl::where('hbl_no', $proposedHblNo)->exists());

                $clonedHbl->hbl_no = $proposedHblNo;
                $clonedHbl->save();

                foreach ($hbl->containers as $container) {
                    $clonedHbl->containers()->attach($container->id, [
                        'pkg_qty' => $container->pivot->pkg_qty ?? null,
                        'pkg_unit' => $container->pivot->pkg_unit ?? null,
                        'weight_kg' => $container->pivot->weight_kg ?? null,
                        'weight_unit' => $container->pivot->weight_unit ?? null,
                        'measure_cbm' => $container->pivot->measure_cbm ?? null,
                        'measure_unit' => $container->pivot->measure_unit ?? null,
                        'po_no' => $container->pivot->po_no ?? null,
                    ]);
                }

                foreach ($hbl->commodities as $commodity) {
                    $clonedHbl->commodities()->create($commodity->toArray());
                }

                foreach ($hbl->receipts as $receipt) {
                    $clonedHbl->receipts()->create($receipt->toArray());
                }
            }

            foreach ($source->charges as $charge) {
                $clonedCharge = $charge->replicate();
                $clonedCharge->ocean_import_id = $oceanImport->id;
                $clonedCharge->invoice_no = null;
                $clonedCharge->invoice_date = null;
                $clonedCharge->is_invoiced = false;
                $clonedCharge->save();
            }

            foreach ($source->memos as $memo) {
                $oceanImport->memos()->create([
                    'subject' => $memo->subject,
                    'content' => $memo->content,
                    'user_id' => auth()->id(),
                ]);
            }

            OceanImportHistory::create([
                'ocean_import_id' => $oceanImport->id,
                'action' => 'Copied',
                'details' => "Shipment copied from ID: {$source->id}",
                'user_id' => auth()->id(),
            ]);

            $oceanImport->load(['hbls.customer', 'hbls.shipper', 'hbls.consignee', 'hbls.containers', 'hbls.commodities', 'hbls.receipts', 'containers.containerType', 'charges.currency', 'memos']);
        }

        return view('ocean-import.index', compact('offices', 'ports', 'vessels', 'agents', 'users', 'containerTypes', 'packageUnits', 'page', 'quotations', 'oceanImport', 'incoterms', 'currencies', 'serviceTerms'));
    }

    public function store(StoreOceanImportRequest $request)
    {
        try {
            // Debug: Log what data is being received
            \Log::info('=== Ocean Import Store START ===');
            \Log::info('Raw Request All:', $request->all());
            \Log::info('Has Containers Key:', ['has' => $request->has('containers')]);
            \Log::info('Containers Input:', ['containers' => $request->input('containers', [])]);
            \Log::info('Validated Data:', $request->validated());
            
            $validatedData = $request->validated();
            \Log::info('Containers in Validated:', [
                'has_key' => isset($validatedData['containers']),
                'count' => isset($validatedData['containers']) ? count($validatedData['containers']) : 0,
                'data' => $validatedData['containers'] ?? []
            ]);
            
            $shipment = $this->oceanImportService->store($validatedData);
            
            \Log::info('Shipment Created:', ['id' => $shipment->id, 'containers_count' => $shipment->containers()->count()]);
            \Log::info('=== Ocean Import Store END ===');

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $shipment->id,
                    'file_no' => $shipment->file_no,
                    'message' => 'Shipment created successfully.'
                ]);
            }

            return redirect()->route('ocean-import.edit', $shipment->id)
                ->with('success', 'Shipment created successfully.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Ocean Import Store - Database Error:', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            // Handle duplicate entry errors
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage = 'This record already exists. ';
                
                // Check which field is duplicate
                if (strpos($e->getMessage(), 'file_no') !== false) {
                    $errorMessage .= 'File No "' . ($request->file_no ?? '') . '" is already used.';
                } elseif (strpos($e->getMessage(), 'mbl_no') !== false) {
                    $errorMessage .= 'MBL No "' . ($request->mbl_no ?? '') . '" is already used.';
                } elseif (strpos($e->getMessage(), 'container_no') !== false) {
                    $errorMessage .= 'One or more container numbers are already used.';
                } elseif (strpos($e->getMessage(), 'hbl_no') !== false) {
                    $errorMessage .= 'One or more HBL numbers are already used.';
                } else {
                    $errorMessage .= 'Please check your entries and try again.';
                }
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                
                return back()->withInput()->with('error', $errorMessage);
            }
            
            // Handle foreign key constraint errors
            if (strpos($e->getMessage(), 'foreign key constraint') !== false || strpos($e->getMessage(), 'Cannot add or update a child row') !== false) {
                $errorMessage = 'Invalid reference: One or more related records do not exist. Please check your selections.';
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                
                return back()->withInput()->with('error', $errorMessage);
            }
            
            // Generic database error
            $errorMessage = 'Unable to save the shipment. Please check your data and try again.';
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return back()->withInput()->with('error', $errorMessage);
            
        } catch (\Exception $e) {
            \Log::error('Ocean Import Store - General Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'An unexpected error occurred. Please try again or contact support if the problem persists.';
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return back()->withInput()->with('error', $errorMessage);
        }
    }

    public function edit(OceanImport $oceanImport)
    {
        $this->authorize('view', $oceanImport);

        $oceanImport->load(['hbls.customer', 'hbls.shipper', 'hbls.consignee', 'hbls.containers', 'hbls.commodities', 'hbls.receipts', 'containers.containerType', 'charges.currency', 'documents', 'memos']);

        $offices = Office::where('is_active', true)->get();
        $ports = Port::all();
        $vessels = Vessel::all();
        $agents = TradePartner::all();
        $users = User::all();
        $containerTypes = ContainerType::all();
        $packageUnits = PackageUnit::all();
        $incoterms = \App\Models\Incoterm::all();
        $currencies = Currency::all();
        $serviceTerms = \App\Models\ServiceTerm::all();
        $quotations = Quotation::with(['customer', 'salesPerson', 'pol', 'pod', 'items.currency'])->latest()->get();

        return view('ocean-import.index', compact('oceanImport', 'offices', 'ports', 'vessels', 'agents', 'users', 'containerTypes', 'packageUnits', 'quotations', 'incoterms', 'currencies', 'serviceTerms'));
    }

    public function update(UpdateOceanImportRequest $request, OceanImport $oceanImport)
    {
        $this->authorize('update', $oceanImport);

        try {
            $this->oceanImportService->update($oceanImport, $request->validated());

            return back()->with('success', 'Shipment updated successfully.');
            
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Ocean Import Update - Database Error:', [
                'id' => $oceanImport->id,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            // Handle duplicate entry errors
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage = 'This record already exists. ';
                
                // Check which field is duplicate
                if (strpos($e->getMessage(), 'file_no') !== false) {
                    $errorMessage .= 'File No "' . ($request->file_no ?? '') . '" is already used by another shipment.';
                } elseif (strpos($e->getMessage(), 'mbl_no') !== false) {
                    $errorMessage .= 'MBL No "' . ($request->mbl_no ?? '') . '" is already used by another shipment.';
                } elseif (strpos($e->getMessage(), 'container_no') !== false) {
                    $errorMessage .= 'One or more container numbers are already used.';
                } elseif (strpos($e->getMessage(), 'hbl_no') !== false) {
                    $errorMessage .= 'One or more HBL numbers are already used.';
                } else {
                    $errorMessage .= 'Please check your entries and try again.';
                }
                
                return back()->withInput()->with('error', $errorMessage);
            }
            
            // Handle foreign key constraint errors
            if (strpos($e->getMessage(), 'foreign key constraint') !== false || strpos($e->getMessage(), 'Cannot add or update a child row') !== false) {
                return back()->withInput()->with('error', 'Invalid reference: One or more related records do not exist. Please check your selections.');
            }
            
            // Generic database error
            return back()->withInput()->with('error', 'Unable to update the shipment. Please check your data and try again.');
            
        } catch (\Exception $e) {
            \Log::error('Ocean Import Update - General Error:', [
                'id' => $oceanImport->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->with('error', 'An unexpected error occurred. Please try again or contact support if the problem persists.');
        }
    }

    public function mblList(Request $request)
    {
        $query = OceanImport::with([
            'office', 'operator', 'carrier', 'vessel',
            'portOfLoading', 'portOfDischarge',
            'dmCustomer', 'overseaAgent',
            'containers.containerType', 'hbls',
        ]);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_no', 'like', "%{$search}%")
                  ->orWhere('mbl_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter_file_no')) {
            $query->where('file_no', 'like', "%{$request->filter_file_no}%");
        }
        if ($request->filled('filter_mbl_no')) {
            $query->where('mbl_no', 'like', "%{$request->filter_mbl_no}%");
        }
        if ($request->filled('filter_etd')) {
            $query->where('etd', 'like', "%{$request->filter_etd}%");
        }
        if ($request->filled('filter_eta')) {
            $query->where('eta', 'like', "%{$request->filter_eta}%");
        }
        if ($request->filled('filter_pol')) {
            $query->whereHas('portOfLoading', fn($q) => $q->where('name', 'like', "%{$request->filter_pol}%"));
        }
        if ($request->filled('filter_pod')) {
            $query->whereHas('portOfDischarge', fn($q) => $q->where('name', 'like', "%{$request->filter_pod}%"));
        }
        if ($request->filled('filter_customer')) {
            $query->whereHas('dmCustomer', fn($q) => $q->where('name', 'like', "%{$request->filter_customer}%"));
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['file_no', 'mbl_no', 'etd', 'eta', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $shipments = $query->orderBy($sortField, $sortDir)->paginate(20)->withQueryString();
        $operators = User::orderBy('name')->get();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $html = view('ocean-import.partials.mbl-list-rows', compact('shipments'))->render();
                $pagination = view('vendor.pagination.custom', ['paginator' => $shipments])->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'pagination' => $pagination,
                    'first' => $shipments->firstItem() ?? 0,
                    'last' => $shipments->lastItem() ?? 0,
                    'total' => $shipments->total(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                ], 500);
            }
        }

        return view('ocean-import.mbl-list', compact('shipments', 'operators'));
    }

    public function hblList(Request $request)
    {
        $query = OceanImportHbl::with([
            'oceanImport.portOfLoading', 'oceanImport.portOfDischarge',
            'customer', 'shipper', 'consignee',
            'containers' => fn($q) => $q->withPivot(['pkg_qty', 'weight_kg', 'measure_cbm']),
        ])
            ->withSum(['oceanImportCharges as ar_balance' => fn($q) => $q->where('type', 'AR')], 'total_amount')
            ->withSum(['oceanImportCharges as ap_balance' => fn($q) => $q->where('type', 'AP')], 'total_amount')
            ->withSum(['oceanImportCharges as dc_balance' => fn($q) => $q->where('type', 'DC_NOTE')], 'total_amount');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('hbl_no', 'like', "%{$search}%")
                  ->orWhereHas('oceanImport', fn($oq) => $oq->where('file_no', 'like', "%{$search}%")
                      ->orWhere('mbl_no', 'like', "%{$search}%"));
            });
        }

        // Filter support
        if ($request->filled('filter_file_no')) {
            $query->whereHas('oceanImport', fn($oq) => $oq->where('file_no', 'like', "%{$request->filter_file_no}%"));
        }
        if ($request->filled('filter_hbl_no')) {
            $query->where('hbl_no', 'like', "%{$request->filter_hbl_no}%");
        }
        if ($request->filled('filter_mbl_no')) {
            $query->whereHas('oceanImport', fn($oq) => $oq->where('mbl_no', 'like', "%{$request->filter_mbl_no}%"));
        }
        if ($request->filled('filter_consignee')) {
            $query->whereHas('consignee', fn($cq) => $cq->where('name', 'like', "%{$request->filter_consignee}%"));
        }

        // Legacy support
        if ($request->filled('file_no')) {
            $query->whereHas('oceanImport', fn($oq) => $oq->where('file_no', 'like', "%{$request->file_no}%"));
        }
        if ($request->filled('hbl_no')) {
            $query->where('hbl_no', 'like', "%{$request->hbl_no}%");
        }
        if ($request->filled('mbl_no')) {
            $query->whereHas('oceanImport', fn($oq) => $oq->where('mbl_no', 'like', "%{$request->mbl_no}%"));
        }
        if ($request->filled('consignee')) {
            $query->whereHas('consignee', fn($cq) => $cq->where('name', 'like', "%{$request->consignee}%"));
        }
        if ($request->filled('consignee_id')) {
            $query->where('consignee_id', $request->consignee_id);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['hbl_no', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $hbls = $query->orderBy($sortField, $sortDir)->paginate(20)->withQueryString();
        $operators = User::orderBy('name')->get();
        $salesPersons = User::orderBy('name')->get();
        $ports = Port::all();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $html = view('ocean-import.partials.hbl-list-rows', compact('hbls'))->render();
                $pagination = view('vendor.pagination.custom', ['paginator' => $hbls])->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'pagination' => $pagination,
                    'first' => $hbls->firstItem() ?? 0,
                    'last' => $hbls->lastItem() ?? 0,
                    'total' => $hbls->total(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                ], 500);
            }
        }

        return view('ocean-import.hbl-list', compact('hbls', 'operators', 'salesPersons', 'ports'));
    }

    public function containerList(Request $request)
    {
        $query = OceanImportContainer::with([
            'oceanImport.office',
            'oceanImport.operator',
            'oceanImport.salesPerson',
            'oceanImport.vessel',
            'oceanImport.carrier',
            'oceanImport.portOfLoading',
            'oceanImport.portOfDischarge',
            'oceanImport.placeOfDelivery',
            'oceanImport.finalDestination',
            'oceanImport.receipt',
            'oceanImport.dmConsignee',
            'oceanImport.dmShipper',
            'oceanImport.dmNotify',
            'oceanImport.dmCustomer',
            'oceanImport.overseaAgent',
            'oceanImport.cfsLocation',
            'oceanImport.cyLocation',
            'oceanImport.hbls.deliveryLocation',
            'containerType',
            'packageUnit',
            'trucker',
        ]);

        $startDate = $request->get('start_date', now()->subMonths(12)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->addMonths(12)->format('Y-m-d'));

        $query->whereHas('oceanImport', function ($q) use ($startDate, $endDate) {
            $q->where(function ($sq) use ($startDate, $endDate) {
                $sq->whereBetween('eta', [$startDate, $endDate])
                   ->orWhereNull('eta');
            });
        });

        if ($request->filled('op_id')) {
            $query->whereHas('oceanImport', function ($q) use ($request) {
                $q->where('op_id', $request->op_id);
            });
        }

        if ($request->filled('stage')) {
            if ($request->stage === 'complete') {
                $query->where('is_complete', true);
            } elseif ($request->stage === 'incomplete') {
                $query->where('is_complete', false);
            }
        }

        $shipMode = $request->get('ship_mode', 'all');
        if ($shipMode === 'FCL') {
            $query->whereHas('oceanImport', function ($q) {
                $q->where('ship_mode', 'FCL');
            });
        } elseif ($shipMode === 'Others') {
            $query->whereHas('oceanImport', function ($q) {
                $q->where('ship_mode', '!=', 'FCL');
            });
        }

        $type = $request->get('type', 'all');
        if ($type === 'overdue') {
            $query->where('fdd', '<', now()->format('Y-m-d'))
                  ->where('is_complete', false);
        } elseif ($type === 'pickup') {
            $query->whereNull('gate_out_date')
                  ->whereNotNull('gate_in_date');
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('container_no', 'like', "%{$search}%")
                  ->orWhere('seal_no', 'like', "%{$search}%")
                  ->orWhereHas('oceanImport', function ($sq) use ($search) {
                      $sq->where('file_no', 'like', "%{$search}%")
                         ->orWhere('mbl_no', 'like', "%{$search}%")
                         ->orWhere('contract_no', 'like', "%{$search}%")
                         ->orWhereHas('vessel', function ($tq) use ($search) {
                             $tq->where('name', 'like', "%{$search}%");
                         })
                         ->orWhereHas('dmConsignee', function ($tq) use ($search) {
                             $tq->where('name', 'like', "%{$search}%");
                         })
                         ->orWhereHas('hbls', function ($tq) use ($search) {
                             $tq->where('hbl_no', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if ($request->filled('filter_file_no')) {
            $query->whereHas('oceanImport', function ($q) use ($request) {
                $q->where('file_no', 'like', "%{$request->filter_file_no}%");
            });
        }
        if ($request->filled('filter_consignee')) {
            $query->whereHas('oceanImport.dmConsignee', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->filter_consignee}%");
            });
        }
        if ($request->filled('filter_hbl_no')) {
            $query->whereHas('oceanImport.hbls', function ($q) use ($request) {
                $q->where('hbl_no', 'like', "%{$request->filter_hbl_no}%");
            });
        }
        if ($request->filled('filter_container_no')) {
            $query->where('container_no', 'like', "%{$request->filter_container_no}%");
        }
        if ($request->filled('filter_etd')) {
            $query->whereHas('oceanImport', function ($q) use ($request) {
                $q->whereDate('etd', $request->filter_etd);
            });
        }
        if ($request->filled('filter_eta')) {
            $query->whereHas('oceanImport', function ($q) use ($request) {
                $q->whereDate('eta', $request->filter_eta);
            });
        }

        $containers = $query->latest()->paginate(20)->withQueryString();
        $users = User::all();
        $truckers = TradePartner::orderBy('name')->get();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $html = view('ocean-import.partials.container-list-rows', compact('containers', 'truckers'))->render();
                $pagination = view('vendor.pagination.custom', ['paginator' => $containers])->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'pagination' => $pagination,
                    'first' => $containers->firstItem() ?? 0,
                    'last' => $containers->lastItem() ?? 0,
                    'total' => $containers->total(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                ], 500);
            }
        }

        return view('ocean-import.containers', compact('containers', 'users', 'truckers', 'startDate', 'endDate'));
    }

    public function updateRemarks(Request $request, OceanImportContainer $container)
    {
        $request->validate([
            'remarks' => 'nullable|string'
        ]);

        $container->update([
            'remarks' => $request->remarks
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Remarks updated successfully.'
        ]);
    }

    public function destroyContainer(OceanImportContainer $container)
    {
        $oceanImportId = $container->ocean_import_id;
        $containerNo = $container->container_no;
        $container->delete();

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Delete Container',
            'details' => "Deleted container: {$containerNo}",
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => "Container {$containerNo} deleted successfully."
        ]);
    }

    public function batchUpdateContainers(Request $request)
    {
        $data = $request->validate([
            'containers' => 'required|array',
            'containers.*.id' => 'required|exists:ocean_import_containers,id',
            'containers.*.container_no' => 'nullable|string',
            'containers.*.pp_ctf' => 'nullable|string',
            'containers.*.seal_no' => 'nullable|string',
            'containers.*.seal_no2' => 'nullable|string',
            'containers.*.lfd' => 'nullable|date',
            'containers.*.fdd' => 'nullable|date',
            'containers.*.storage_start_date' => 'nullable|date',
            'containers.*.storage_end_date' => 'nullable|date',
            'containers.*.unload_vessel_date' => 'nullable|date',
            'containers.*.gate_in_date' => 'nullable|date',
            'containers.*.rail_start_date' => 'nullable|date',
            'containers.*.pod_eta' => 'nullable|date',
            'containers.*.appointment_date' => 'nullable|date',
            'containers.*.pickup_date' => 'nullable|date',
            'containers.*.gate_out_date' => 'nullable|date',
            'containers.*.fdest_eta' => 'nullable|date',
            'containers.*.eta_door' => 'nullable|date',
            'containers.*.ata_door' => 'nullable|date',
            'containers.*.empty_conf_date' => 'nullable|date',
            'containers.*.empty_ret_date' => 'nullable|date',
            'containers.*.an_sent_date' => 'nullable|date',
            'containers.*.do_sent_date' => 'nullable|date',
            'containers.*.pkg_qty' => 'nullable|numeric',
            'containers.*.weight_kg' => 'nullable|numeric',
            'containers.*.weight_lb' => 'nullable|numeric',
            'containers.*.measure_cbm' => 'nullable|numeric',
            'containers.*.measure_cft' => 'nullable|numeric',
            'containers.*.chassis_days' => 'nullable|numeric',
            'containers.*.pickup_no' => 'nullable|string',
            'containers.*.cprs_no' => 'nullable|string',
            'containers.*.cnru_no' => 'nullable|string',
            'containers.*.it_no' => 'nullable|string',
            'containers.*.yard_location' => 'nullable|string',
            'containers.*.is_dg' => 'nullable|boolean',
            'containers.*.is_carrier_release' => 'nullable|boolean',
            'containers.*.is_avail_pickup' => 'nullable|boolean',
            'containers.*.is_complete' => 'nullable|boolean',
            'containers.*.is_customs_hold' => 'nullable|boolean',
            'containers.*.is_an_sent' => 'nullable|boolean',
            'containers.*.is_do_sent' => 'nullable|boolean',
            'containers.*.remarks' => 'nullable|string',
            'containers.*.internal_remarks' => 'nullable|string',
        ]);

        $stringFields = [
            'container_no', 'pp_ctf', 'seal_no', 'seal_no2', 'pickup_no',
            'cprs_no', 'cnru_no', 'it_no', 'yard_location', 'remarks', 'internal_remarks',
        ];
        $numericFields = [
            'pkg_qty', 'weight_kg', 'weight_lb', 'measure_cbm', 'measure_cft', 'chassis_days',
        ];
        $booleanFields = [
            'is_dg', 'is_carrier_release', 'is_avail_pickup', 'is_complete',
            'is_customs_hold', 'is_an_sent', 'is_do_sent',
        ];
        $dateFields = [
            'lfd', 'fdd', 'storage_start_date', 'storage_end_date',
            'unload_vessel_date', 'gate_in_date', 'rail_start_date', 'pod_eta',
            'appointment_date', 'pickup_date', 'gate_out_date', 'fdest_eta',
            'eta_door', 'ata_door', 'empty_conf_date', 'empty_ret_date',
            'an_sent_date', 'do_sent_date',
        ];

        $updated = 0;
        foreach ($data['containers'] as $item) {
            $id = $item['id'];
            unset($item['id']);

            foreach ($stringFields as $field) {
                if (array_key_exists($field, $item) && $item[$field] === '') {
                    $item[$field] = null;
                }
            }
            foreach ($numericFields as $field) {
                if (array_key_exists($field, $item) && ($item[$field] === '' || $item[$field] === null)) {
                    $item[$field] = null;
                }
            }
            foreach ($booleanFields as $field) {
                if (array_key_exists($field, $item)) {
                    $item[$field] = filter_var($item[$field], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                }
            }
            foreach ($dateFields as $field) {
                if (array_key_exists($field, $item) && ($item[$field] === '' || $item[$field] === null)) {
                    $item[$field] = null;
                }
            }

            OceanImportContainer::where('id', $id)->update($item);
            $updated++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$updated} container(s) updated successfully."
        ]);
    }

    public function batchUpdateInline(Request $request)
    {
        $containers = $request->input('containers', []);
        $updated = 0;

        foreach ($containers as $containerId => $fields) {
            $container = OceanImportContainer::find($containerId);
            if ($container) {
                // Sanitize empty strings to null for dates
                foreach ($fields as $key => $value) {
                    if ($value === '' || $value === 'null') {
                        $fields[$key] = null;
                    }
                }
                
                $container->update($fields);
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$updated} container(s) updated successfully."
        ]);
    }

    public function destroy(OceanImport $oceanImport)
    {
        $this->authorize('delete', $oceanImport);

        $oceanImport->delete();
        return redirect()->route('ocean-import.index')->with('success', 'Shipment deleted.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('ocean-import.index');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:ocean_imports,id'
        ]);

        $count = OceanImport::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} shipment(s) deleted successfully."
        ]);
    }

    public function bulkBlock(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('ocean-import.index');
        }
        
        $type = $request->input('type', 'mbl'); // default to MBL
        
        if ($type === 'hbl') {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:ocean_import_hbls,id'
            ]);
            
            // For HBLs, update the parent shipment's is_hold field
            $hbls = OceanImportHbl::whereIn('id', $request->ids)->get();
            $shipmentIds = $hbls->pluck('ocean_import_id')->unique();
            OceanImport::whereIn('id', $shipmentIds)->update(['is_hold' => true]);
        } else {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:ocean_imports,id'
            ]);
            
            OceanImport::whereIn('id', $request->ids)->update(['is_hold' => true]);
        }
        
        $count = count($request->ids);
        return response()->json([
            'success' => true,
            'message' => "{$count} " . ($type === 'hbl' ? 'HBL(s)' : 'shipment(s)') . " blocked successfully."
        ]);
    }

    public function bulkUnblock(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('ocean-import.index');
        }
        
        $type = $request->input('type', 'mbl'); // default to MBL
        
        if ($type === 'hbl') {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:ocean_import_hbls,id'
            ]);
            
            // For HBLs, update the parent shipment's is_hold field
            $hbls = OceanImportHbl::whereIn('id', $request->ids)->get();
            $shipmentIds = $hbls->pluck('ocean_import_id')->unique();
            OceanImport::whereIn('id', $shipmentIds)->update(['is_hold' => false]);
        } else {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:ocean_imports,id'
            ]);
            
            OceanImport::whereIn('id', $request->ids)->update(['is_hold' => false]);
        }
        
        $count = count($request->ids);
        return response()->json([
            'success' => true,
            'message' => "{$count} " . ($type === 'hbl' ? 'HBL(s)' : 'shipment(s)') . " unblocked successfully."
        ]);
    }

    public function bulkChangeOp(Request $request)
    {
        $isHbl = $request->type === 'hbl';
        $ids = $request->ids;

        if ($isHbl) {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:ocean_import_hbls,id',
                'op_id' => 'required|integer|exists:users,id',
            ]);
            $shipmentIds = OceanImportHbl::whereIn('id', $ids)->pluck('ocean_import_id')->unique()->values()->toArray();
        } else {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:ocean_imports,id',
                'op_id' => 'required|integer|exists:users,id',
            ]);
            $shipmentIds = $ids;
        }

        OceanImport::whereIn('id', $shipmentIds)->update(['op_id' => $request->op_id]);
        $count = count($shipmentIds);
        return response()->json(['success' => true, 'message' => "OP changed for {$count} shipment(s)."]);
    }

    public function bulkChangeSales(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:ocean_import_hbls,id',
            'sales_person_id' => 'required|integer|exists:users,id',
        ]);
        OceanImportHbl::whereIn('id', $request->ids)->update(['sales_person_id' => $request->sales_person_id]);
        $count = count($request->ids);
        return response()->json(['success' => true, 'message' => "Sales changed for {$count} HBL(s)."]);
    }

    public function updateColor(Request $request, OceanImport $oceanImport)
    {
        $request->validate([
            'color' => 'nullable|string|max:20',
        ]);

        $oceanImport->update(['color' => $request->color]);

        return response()->json(['success' => true, 'color' => $oceanImport->color]);
    }

    public function exportCsv(Request $request)
    {
        $query = OceanImport::with([
            'office', 'operator', 'vessel', 'carrier',
            'portOfLoading', 'portOfDischarge',
            'dmCustomer', 'overseaAgent', 'containers', 'hbls',
        ]);

        $this->applyFiltersToQuery($query, $request);

        $shipments = $query->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ocean-import-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($shipments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'File No.', 'MBL No.', 'Sub B/L No.', 'Office', 'Operator',
                'Carrier', 'Vessel', 'Voyage', 'Port of Loading', 'Port of Discharge',
                'Customer', 'Oversea Agent', 'ETD', 'ETA', 'Final ETA',
                'Freight Term', 'O.B/L Type', 'M.B/L Type',
                'Containers', 'HBLs', 'Post Date', 'Created At',
            ]);

            foreach ($shipments as $s) {
                fputcsv($file, [
                    $s->file_no,
                    $s->mbl_no,
                    $s->sub_bl_no,
                    $s->office->code ?? '--',
                    $s->operator->name ?? '--',
                    $s->carrier->name ?? '--',
                    $s->vessel->name ?? '--',
                    $s->voyage,
                    $s->portOfLoading->name ?? '--',
                    $s->portOfDischarge->name ?? '--',
                    $s->dmCustomer->name ?? '--',
                    $s->overseaAgent->name ?? '--',
                    $s->etd ? $s->etd->format('m-d-Y') : '--',
                    $s->eta ? $s->eta->format('m-d-Y') : '--',
                    $s->final_eta ? $s->final_eta->format('m-d-Y') : '--',
                    $s->freight_term,
                    $s->obl_type,
                    $s->bl_type,
                    $s->containers->count(),
                    $s->hbls()->count(),
                    $s->post_date ? $s->post_date->format('m-d-Y') : '--',
                    $s->created_at->format('m-d-Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // --- Container Methods ---

    public function importContainers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        $file = $request->file('file');
        $containers = [];

        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $containers[] = [
                    'container_no' => $row[0] ?? '',
                    'pp_ctf' => $row[1] ?? '',
                    'seal_no' => $row[2] ?? '',
                    'seal_no2' => $row[3] ?? '',
                    'pkg_qty' => intval($row[4] ?? 0),
                    'weight_kg' => floatval($row[5] ?? 0.00),
                    'measure_cbm' => floatval($row[6] ?? 0.00),
                ];
            }
            fclose($handle);
        }

        return response()->json([
            'success' => true,
            'containers' => $containers
        ]);
    }

    public function duplicateContainer($id)
    {
        $container = OceanImportContainer::findOrFail($id);
        $this->authorize('update', OceanImport::findOrFail($container->ocean_import_id));
        $clone = $container->replicate();
        $clone->container_no = $clone->container_no . ' - Copy';
        $clone->save();

        OceanImportHistory::create([
            'ocean_import_id' => $container->ocean_import_id,
            'action' => 'Duplicate Container',
            'details' => "Duplicated container: {$container->container_no} -> {$clone->container_no}",
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'container' => $clone
        ]);
    }

    // --- Charge Methods ---

    public function duplicateCharges(Request $request, $oceanImportId)
    {
        $this->authorize('update', OceanImport::findOrFail($oceanImportId));

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:ocean_import_charges,id'
        ]);

        $duplicated = [];
        foreach ($request->ids as $id) {
            $charge = OceanImportCharge::findOrFail($id);
            $clone = $charge->replicate();
            $clone->invoice_no = null;
            $clone->invoice_date = null;
            $clone->is_invoiced = false;
            $clone->save();
            $duplicated[] = $clone;
        }

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Duplicate Charges',
            'details' => "Duplicated " . count($request->ids) . " charges.",
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'charges' => $duplicated
        ]);
    }

    public function bulkUpdateCurrency(Request $request, $oceanImportId)
    {
        $request->validate([
            'currency' => 'required|string|max:10',
            'ids' => 'nullable|array',
            'ids.*' => 'exists:ocean_import_charges,id'
        ]);

        $currency = Currency::where('code', $request->currency)->first();
        if (!$currency) {
            return response()->json(['success' => false, 'message' => 'Currency not found.'], 404);
        }

        $query = OceanImportCharge::where('ocean_import_id', $oceanImportId);
        if ($request->has('ids') && is_array($request->ids)) {
            $query->whereIn('id', $request->ids);
        }
        $query->update(['currency_id' => $currency->id]);

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Bulk Update Currency',
            'details' => "Updated currency of charges to {$request->currency}.",
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function applyVatToAll(Request $request, $oceanImportId)
    {
        $request->validate([
            'vat' => 'required|numeric|min:0|max:100',
            'ids' => 'nullable|array',
            'ids.*' => 'exists:ocean_import_charges,id'
        ]);

        $query = OceanImportCharge::where('ocean_import_id', $oceanImportId);
        if ($request->has('ids') && is_array($request->ids)) {
            $query->whereIn('id', $request->ids);
        }

        $charges = $query->get();
        foreach ($charges as $charge) {
            $taxAmount = $charge->amount * ($request->vat / 100);
            $charge->update([
                'tax_percent' => $request->vat,
                'tax_amount' => $taxAmount,
                'vat' => $request->vat,
                'total_amount' => $charge->amount + $taxAmount
            ]);
        }

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Apply VAT',
            'details' => "Applied VAT {$request->vat}% to charges.",
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function prorataCharges(Request $request, $oceanImportId)
    {
        $request->validate([
            'basis' => 'required|in:volume,weight',
            'charge_id' => 'required|exists:ocean_import_charges,id'
        ]);

        $shipment = OceanImport::with(['hbls.containers', 'containers'])->findOrFail($oceanImportId);
        $charge = OceanImportCharge::findOrFail($request->charge_id);

        $totalVal = 0;
        if ($request->basis == 'volume') {
            $totalVal = $shipment->containers->sum('measure_cbm');
        } else {
            $totalVal = $shipment->containers->sum('weight_kg');
        }

        if ($totalVal <= 0) {
            return response()->json(['success' => false, 'message' => 'Total basis value is zero, cannot prorate.'], 400);
        }

        $hbls = $shipment->hbls;
        if ($hbls->count() == 0) {
            return response()->json(['success' => false, 'message' => 'No House BLs found to prorate charges across.'], 400);
        }

        foreach ($hbls as $hbl) {
            $hblVal = 0;
            if ($request->basis == 'volume') {
                $hblVal = $hbl->containers->sum('pivot.measure_cbm') ?: $hbl->containers->sum('measure_cbm');
            } else {
                $hblVal = $hbl->containers->sum('pivot.weight_kg') ?: $hbl->containers->sum('weight_kg');
            }
            $fraction = $totalVal > 0 ? $hblVal / $totalVal : 0;

            OceanImportCharge::create([
                'ocean_import_id' => $oceanImportId,
                'ocean_import_hbl_id' => $hbl->id,
                'type' => $charge->type,
                'charge_code' => $charge->charge_code,
                'charge_name' => $charge->charge_name,
                'bill_to_id' => $charge->bill_to_id,
                'vendor_id' => $charge->vendor_id,
                'pc' => $charge->pc,
                'qty' => max($fraction, 0.001),
                'unit' => $charge->unit,
                'currency_id' => $charge->currency_id,
                'rate' => $charge->rate,
                'amount' => $charge->amount * $fraction,
                'tax_percent' => $charge->tax_percent,
                'tax_amount' => $charge->tax_amount * $fraction,
                'total_amount' => $charge->total_amount * $fraction,
                'roe' => $charge->roe,
                'vat' => $charge->vat,
                'remark' => "Prorated from master charge: {$charge->charge_code}"
            ]);
        }

        $charge->delete();

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Prorata Charges',
            'details' => "Prorated charge {$charge->charge_code} across House BLs by {$request->basis}.",
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function applyChargeTemplate(Request $request, $oceanImportId)
    {
        $currency = Currency::where('code', 'USD')->first();
        $templates = [
            ['code' => 'OFT', 'name' => 'Ocean Freight', 'rate' => 1200, 'qty' => 1, 'type' => 'AR'],
            ['code' => 'THC', 'name' => 'Terminal Handling Charge', 'rate' => 350, 'qty' => 1, 'type' => 'AR'],
            ['code' => 'DOC', 'name' => 'Documentation Fee', 'rate' => 75, 'qty' => 1, 'type' => 'AR'],
        ];

        $shipment = OceanImport::findOrFail($oceanImportId);
        $existingCodes = $shipment->charges()->pluck('charge_code')->toArray();

        foreach ($templates as $tc) {
            if (in_array($tc['code'], $existingCodes)) continue;

            OceanImportCharge::create([
                'ocean_import_id' => $oceanImportId,
                'type' => $tc['type'],
                'charge_code' => $tc['code'],
                'charge_name' => $tc['name'],
                'pc' => 'COLLECT',
                'qty' => $tc['qty'],
                'unit' => 'UNIT',
                'currency_id' => $currency->id ?? null,
                'rate' => $tc['rate'],
                'amount' => $tc['rate'] * $tc['qty'],
                'total_amount' => $tc['rate'] * $tc['qty'],
                'roe' => 1.0000,
            ]);
        }

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Apply Template',
            'details' => "Applied standard ocean import charges template.",
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function copyChargesFromQuote(Request $request, $oceanImportId)
    {
        $request->validate([
            'quote_id' => 'required|exists:quotations,id'
        ]);

        $quote = Quotation::with('items')->findOrFail($request->quote_id);
        $currency = Currency::where('code', 'USD')->first();
        $shipment = OceanImport::findOrFail($oceanImportId);

        $createdCount = 0;

        if ($quote->items && $quote->items->count() > 0) {
            foreach ($quote->items as $item) {
                OceanImportCharge::create([
                    'ocean_import_id' => $oceanImportId,
                    'type' => 'AR',
                    'charge_code' => $item->charge_code ?? 'QTE',
                    'charge_name' => $item->description ?? $item->charge_code ?? 'Quote Item',
                    'pc' => $item->pc ?? 'COLLECT',
                    'qty' => $item->qty ?? 1,
                    'unit' => $item->unit ?? 'UNIT',
                    'currency_id' => $currency->id ?? null,
                    'rate' => $item->rate ?? 0,
                    'amount' => ($item->rate ?? 0) * ($item->qty ?? 1),
                    'total_amount' => ($item->rate ?? 0) * ($item->qty ?? 1),
                    'roe' => 1.0000,
                    'remark' => "Copied from Quote ID: {$request->quote_id}",
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0) {
            OceanImportCharge::create([
                'ocean_import_id' => $oceanImportId,
                'type' => 'AR',
                'charge_code' => 'OFT',
                'charge_name' => 'Ocean Freight (From Quote)',
                'pc' => 'COLLECT',
                'qty' => 1,
                'unit' => 'UNIT',
                'currency_id' => $currency->id ?? null,
                'rate' => 950.00,
                'amount' => 950.00,
                'total_amount' => 950.00,
                'roe' => 1.0000,
                'remark' => "Copied from Quote ID: {$request->quote_id}"
            ]);
            $createdCount = 1;
        }

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Copy Quote Charges',
            'details' => "Copied {$createdCount} charge(s) from Quote ID: {$request->quote_id}.",
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true, 'created' => $createdCount]);
    }

    public function deleteAllCharges($oceanImportId)
    {
        $this->authorize('update', OceanImport::findOrFail($oceanImportId));

        OceanImportCharge::where('ocean_import_id', $oceanImportId)->delete();

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Delete All Charges',
            'details' => "Deleted all charges.",
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function exportChargesToExcel($oceanImportId)
    {
        $charges = OceanImportCharge::with(['billTo', 'vendor'])->where('ocean_import_id', $oceanImportId)->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="charges-' . $oceanImportId . '-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($charges) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Party', 'Type', 'P/C', 'Code', 'Rate', 'Qty', 'Unit', 'Amount', 'ROE', 'VAT', 'Total Amount', 'Invoice No', 'Remark']);
            foreach ($charges as $c) {
                fputcsv($file, [
                    $c->type === 'AP' ? ($c->vendor->name ?? '--') : ($c->billTo->name ?? '--'),
                    $c->type,
                    $c->pc,
                    $c->charge_code,
                    $c->rate,
                    $c->qty,
                    $c->unit,
                    $c->amount,
                    $c->roe,
                    $c->vat,
                    $c->total_amount,
                    $c->invoice_no,
                    $c->remark
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printCharges($oceanImportId)
    {
        $shipment = OceanImport::with(['office', 'operator', 'vessel', 'portOfLoading', 'portOfDischarge', 'charges.currency'])->findOrFail($oceanImportId);
        return view('ocean-import.print-charges', compact('shipment'));
    }

    public function createInvoiceFromCharges(Request $request, $oceanImportId)
    {
        $this->authorize('update', OceanImport::findOrFail($oceanImportId));

        $charges = OceanImportCharge::where('ocean_import_id', $oceanImportId)
            ->where('is_invoiced', false)
            ->get();

        if ($charges->count() == 0) {
            return response()->json(['success' => false, 'message' => 'No uninvoiced charges found.'], 400);
        }

        $invNo = 'INV-' . strtoupper(uniqid());
        foreach ($charges as $charge) {
            $charge->update([
                'is_invoiced' => true,
                'invoice_no' => $invNo,
                'invoice_date' => now()
            ]);
        }

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Create Invoice',
            'details' => "Created invoice {$invNo} for " . $charges->count() . " charges.",
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'invoice_no' => $invNo
        ]);
    }

    // --- Document Methods ---

    public function uploadDocument(Request $request, $oceanImportId)
    {
        $this->authorize('update', OceanImport::findOrFail($oceanImportId));

        $request->validate([
            'file' => 'required|file|max:10240',
            'description' => 'nullable|string|max:255'
        ]);

        $file = $request->file('file');
        $path = $file->store('shipments/documents', 'public');

        $doc = OceanImportDocument::create([
            'ocean_import_id' => $oceanImportId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'uploaded_by' => auth()->id()
        ]);

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Upload Document',
            'details' => "Uploaded document: {$doc->file_name}",
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'document' => $doc
        ]);
    }

    public function uploadDocumentTemp(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        $file = $request->file('file');
        $path = $file->store('shipments/temp', 'public');

        return response()->json([
            'success' => true,
            'document' => [
                'id' => null,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_extension' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'description' => 'Temporary uploaded file',
                'created_at' => now()->toISOString()
            ]
        ]);
    }

    public function deleteDocument($id)
    {
        $doc = OceanImportDocument::findOrFail($id);
        $this->authorize('update', OceanImport::findOrFail($doc->ocean_import_id));
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return response()->json(['success' => true]);
    }

    public function downloadDocument($id)
    {
        $doc = OceanImportDocument::findOrFail($id);
        return Storage::disk('public')->download($doc->file_path, $doc->file_name);
    }

    // --- Memo Methods ---

    public function addMemo(Request $request, $oceanImportId)
    {
        $this->authorize('update', OceanImport::findOrFail($oceanImportId));

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'nullable|string'
        ]);

        $memo = OceanImportMemo::create([
            'ocean_import_id' => $oceanImportId,
            'subject' => $request->subject,
            'content' => $request->content,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'memo' => $memo->load('user')
        ]);
    }

    public function updateMemo(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'nullable|string'
        ]);

        $memo = OceanImportMemo::findOrFail($id);
        $this->authorize('update', OceanImport::findOrFail($memo->ocean_import_id));
        $memo->update([
            'subject' => $request->subject,
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'memo' => $memo
        ]);
    }

    public function deleteMemo($id)
    {
        $memo = OceanImportMemo::findOrFail($id);
        $this->authorize('update', OceanImport::findOrFail($memo->ocean_import_id));
        $memo->delete();

        return response()->json(['success' => true]);
    }

    // --- Filing Method ---

    public function updateFiling(Request $request, $oceanImportId)
    {
        $shipment = OceanImport::findOrFail($oceanImportId);
        $this->authorize('update', $shipment);

        $request->validate([
            'ams_no' => 'nullable|string|max:255',
            'isf_no' => 'nullable|string|max:255',
            'isf_matched_date' => 'nullable|date',
            'is_isf_3rd_party' => 'nullable|boolean',
            'entry_no' => 'nullable|string|max:255',
            'entry_doc_sent_date' => 'nullable|date',
            'go_date' => 'nullable|date',
            'available_date' => 'nullable|date',
            'c_released_date' => 'nullable|date',
            'released_by_id' => 'nullable|exists:users,id',
            'is_ror' => 'nullable|boolean',
            'is_hold' => 'nullable|boolean',
            'door_delivery_date' => 'nullable|date',
            'trucker_id' => 'nullable|exists:trade_partners,id',
            'expiry_date' => 'nullable|date',
            'sales_type' => 'nullable|string|max:255',
            'incoterm_id' => 'nullable|string|max:255',
        ]);

        $shipment->update($request->only([
            'ams_no', 'isf_no', 'isf_matched_date', 'is_isf_3rd_party', 'entry_no',
            'entry_doc_sent_date', 'go_date', 'available_date', 'c_released_date',
            'released_by_id', 'is_ror', 'is_hold', 'door_delivery_date', 'trucker_id',
            'expiry_date', 'sales_type', 'incoterm_id'
        ]));

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Update Filing',
            'details' => 'Filing details updated.',
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Filing details updated successfully.'
        ]);
    }

    // --- Quote Methods ---

    public function searchQuotations(Request $request)
    {
        $q = $request->get('q');
        $quotes = Quotation::with(['customer', 'salesPerson', 'pol', 'pod'])
            ->where('quote_no', 'LIKE', "%{$q}%")
            ->latest()
            ->get();

        return response()->json($quotes);
    }

    public function searchWarehouseReceipts(Request $request)
    {
        $q = $request->get('q');
        $query = WarehouseReceipt::with(['customer', 'shipper']);

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('receipt_no', 'LIKE', "%{$q}%")
                     ->orWhere('tracking_no', 'LIKE', "%{$q}%")
                     ->orWhere('carrier_name', 'LIKE', "%{$q}%")
                     ->orWhereHas('customer', fn($cq) => $cq->where('name', 'LIKE', "%{$q}%"))
                     ->orWhereHas('shipper', fn($sq) => $sq->where('name', 'LIKE', "%{$q}%"));
            });
        }

        $receipts = $query->latest()->limit(50)->get()->map(function ($wr) {
            return [
                'id' => $wr->id,
                'receipt_no' => $wr->receipt_no,
                'vin_no' => $wr->vin_no ?? ($wr->tracking_no ?? 'N/A'),
                'total_pcs' => $wr->total_pcs ?? $wr->items()->sum('qty') ?? 0,
                'available_pcs' => $wr->total_pcs ?? 0,
                'allocated_pcs' => 0,
                'unit' => $wr->unit ?? 'PCS',
                'actual_weight' => $wr->actual_weight ?? 0,
                'measurement' => $wr->measurement ?? 0,
                'remarks' => $wr->internal_remark ?? 'Ready for load plan',
            ];
        });

        return response()->json($receipts);
    }

    public function loadQuoteToShipment(Request $request, $oceanImportId)
    {
        $request->validate(['quote_id' => 'required|exists:quotations,id']);
        $quote = Quotation::findOrFail($request->quote_id);

        $shipment = OceanImport::findOrFail($oceanImportId);
        $this->authorize('update', $shipment);
        $shipment->update([
            'dm_customer_id' => $quote->customer_id,
            'dm_sales_person_id' => $quote->sales_person_id,
            'pol_id' => $quote->pol_id,
            'pod_id' => $quote->pod_id,
            'freight_term' => $quote->freight_term ?? 'Prepaid',
        ]);

        OceanImportHistory::create([
            'ocean_import_id' => $oceanImportId,
            'action' => 'Load Quote',
            'details' => "Loaded details from Quote No: {$quote->quote_no}.",
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quote details successfully loaded into shipment.'
        ]);
    }

    // --- History / Printing / PDF Methods ---

    public function getShipmentHistory($oceanImportId)
    {
        $history = OceanImportHistory::with('user')
            ->where('ocean_import_id', $oceanImportId)
            ->latest()
            ->get();
        return response()->json($history);
    }

    public function exportShipmentPdf($id)
    {
        $shipment = OceanImport::with([
            'office', 'operator', 'carrier', 'vessel',
            'portOfLoading', 'portOfDischarge',
            'dmCustomer', 'dmConsignee', 'overseaAgent',
            'containers.containerType', 'hbls',
        ])->findOrFail($id);

        return view('ocean-import.print-pdf', compact('shipment'));
    }

    public function exportContainersCsv(Request $request)
    {
        $query = OceanImportContainer::with([
            'oceanImport.office',
            'oceanImport.operator',
            'oceanImport.salesPerson',
            'oceanImport.vessel',
            'oceanImport.carrier',
            'oceanImport.portOfLoading',
            'oceanImport.portOfDischarge',
            'oceanImport.placeOfDelivery',
            'oceanImport.finalDestination',
            'oceanImport.dmConsignee',
            'oceanImport.dmShipper',
            'oceanImport.dmNotify',
            'oceanImport.dmCustomer',
            'oceanImport.overseaAgent',
            'oceanImport.cfsLocation',
            'oceanImport.cyLocation',
            'oceanImport.hbls',
            'containerType',
            'packageUnit',
            'trucker',
        ]);

        $startDate = $request->get('start_date', now()->subMonths(12)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->addMonths(12)->format('Y-m-d'));

        $query->whereHas('oceanImport', function ($q) use ($startDate, $endDate) {
            $q->where(function ($sq) use ($startDate, $endDate) {
                $sq->whereBetween('eta', [$startDate, $endDate])
                   ->orWhereNull('eta');
            });
        });

        if ($request->filled('op_id')) {
            $query->whereHas('oceanImport', fn($q) => $q->where('op_id', $request->op_id));
        }

        $shipMode = $request->get('ship_mode', 'all');
        if ($shipMode === 'FCL') {
            $query->whereHas('oceanImport', fn($q) => $q->where('ship_mode', 'FCL'));
        } elseif ($shipMode === 'Others') {
            $query->whereHas('oceanImport', fn($q) => $q->where('ship_mode', '!=', 'FCL'));
        }

        $type = $request->get('type', 'all');
        if ($type === 'overdue') {
            $query->where('fdd', '<', now()->format('Y-m-d'))->where('is_complete', false);
        } elseif ($type === 'pickup') {
            $query->whereNull('gate_out_date')->whereNotNull('gate_in_date');
        }

        $containers = $query->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="containers-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($containers) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, [
                'File No.', 'Container No.', 'TP/SZ', 'PP/CTF', 'Seal No.', 'Seal No. 2',
                'LFD', 'FDD', 'PKG', 'Weight(KG)', 'Weight(LB)', 'Meas(CBM)', 'Meas(CFT)',
                'Remark', 'D.G', 'Complete', 'CY/CFS Location', 'Consignee', 'HB/L No.',
                'MB/L NO.', 'Carrier', 'Vessel', 'POL', 'POD', 'Voyage', 'Customer',
                'Gate In', 'Gate Out', 'Pick Up', 'Appt.', 'P.O.D ETA', 'F.Dest ETA',
                'ETA Door', 'ATA Door', 'Empty Conf.', 'Empty Ret.', 'Storage Start', 'Storage End',
                'Pick No.', 'CPRS No.', 'CNRU No.', 'Yard Location', 'Avail Pickup',
                'Trucker', 'Chassis Days', 'C.Hold', 'A/N', 'D/O',
            ]);

            foreach ($containers as $c) {
                fputcsv($file, [
                    $c->oceanImport->file_no ?? '',
                    $c->container_no ?? '',
                    $c->containerType->code ?? '',
                    $c->pp_ctf ?? '',
                    $c->seal_no ?? '',
                    $c->seal_no2 ?? '',
                    $c->lfd ? $c->lfd->format('Y-m-d') : '',
                    $c->fdd ? $c->fdd->format('Y-m-d') : '',
                    $c->pkg_qty ?? '',
                    $c->weight_kg ?? '',
                    $c->weight_lb ?? '',
                    $c->measure_cbm ?? '',
                    $c->measure_cft ?? '',
                    $c->remarks ?? '',
                    $c->is_dg ? 'Yes' : 'No',
                    $c->is_complete ? 'Yes' : 'No',
                    $c->oceanImport->cfsLocation->name ?? ($c->oceanImport->cyLocation->name ?? ''),
                    $c->oceanImport->dmConsignee->name ?? '',
                    $c->oceanImport->hbls->count() ? $c->oceanImport->hbls->pluck('hbl_no')->join(', ') : '',
                    $c->oceanImport->mbl_no ?? '',
                    $c->oceanImport->carrier->name ?? '',
                    $c->oceanImport->vessel->name ?? '',
                    $c->oceanImport->portOfLoading->name ?? '',
                    $c->oceanImport->portOfDischarge->name ?? '',
                    $c->oceanImport->voyage ?? '',
                    $c->oceanImport->dmCustomer->name ?? '',
                    $c->gate_in_date ? $c->gate_in_date->format('Y-m-d') : '',
                    $c->gate_out_date ? $c->gate_out_date->format('Y-m-d') : '',
                    $c->pickup_date ? $c->pickup_date->format('Y-m-d') : '',
                    $c->appointment_date ? $c->appointment_date->format('Y-m-d') : '',
                    $c->pod_eta ? $c->pod_eta->format('Y-m-d') : '',
                    $c->fdest_eta ? $c->fdest_eta->format('Y-m-d') : '',
                    $c->eta_door ? $c->eta_door->format('Y-m-d') : '',
                    $c->ata_door ? $c->ata_door->format('Y-m-d') : '',
                    $c->empty_conf_date ? $c->empty_conf_date->format('Y-m-d') : '',
                    $c->empty_ret_date ? $c->empty_ret_date->format('Y-m-d') : '',
                    $c->storage_start_date ? $c->storage_start_date->format('Y-m-d') : '',
                    $c->storage_end_date ? $c->storage_end_date->format('Y-m-d') : '',
                    $c->pickup_no ?? '',
                    $c->cprs_no ?? '',
                    $c->cnru_no ?? '',
                    $c->yard_location ?? '',
                    $c->is_avail_pickup ? 'Yes' : 'No',
                    $c->trucker->name ?? '',
                    $c->chassis_days ?? '',
                    $c->is_customs_hold ? 'Yes' : 'No',
                    $c->is_an_sent ? 'Yes' : 'No',
                    $c->is_do_sent ? 'Yes' : 'No',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportContainerList($id)
    {
        $shipment = OceanImport::with('containers.containerType')->findOrFail($id);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="containers-' . $shipment->file_no . '-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($shipment) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Container No', 'PP/CTF', 'Type', 'Seal No', 'Seal No2', 'LFD', 'FDD', 'PKG Qty', 'Weight KG', 'Measure CBM']);
            foreach ($shipment->containers as $c) {
                fputcsv($file, [
                    $c->container_no,
                    $c->pp_ctf,
                    $c->containerType->code ?? '',
                    $c->seal_no,
                    $c->seal_no2,
                    $c->lfd ? $c->lfd->format('Y-m-d') : '',
                    $c->fdd ? $c->fdd->format('Y-m-d') : '',
                    $c->pkg_qty,
                    $c->weight_kg,
                    $c->measure_cbm
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
