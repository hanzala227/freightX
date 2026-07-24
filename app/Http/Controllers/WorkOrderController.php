<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\TradePartner;
use App\Models\OceanBooking;
use App\Models\AirBooking;
use App\Models\Port;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with(['workable', 'vendor'])
            ->latest()
            ->paginate(20);
        return view('ocean-export.work-order-list', compact('workOrders'));
    }

    public function apiIndex(Request $request)
    {
        $request->validate([
            'workable_type' => 'required|string',
            'workable_id'   => 'required|integer',
        ]);

        $workOrders = WorkOrder::where('workable_type', $request->workable_type)
            ->where('workable_id', $request->workable_id)
            ->with('vendor')
            ->latest()
            ->get()
            ->map(function ($wo) {
                return [
                    'id' => $wo->id,
                    'no' => $wo->work_order_no,
                    'type' => $wo->subject ?? 'PICKUP & DELIVERY ORDER',
                    'trucker' => $wo->vendor->name ?? 'N/A',
                    'date' => $wo->issue_date ? $wo->issue_date->format('m/d/Y') : ($wo->created_at ? $wo->created_at->format('m/d/Y') : 'N/A'),
                ];
            });

        return response()->json($workOrders);
    }

    public function create(Request $request)
    {
        $workableType = $request->query('workable_type');
        $workableId = $request->query('workable_id');

        $workable = null;
        $prefilledData = [];

        if ($workableType && $workableId) {
            if ($workableType === 'App\Models\OceanBooking' || $workableType === 'OceanBooking') {
                $workableType = \App\Models\OceanBooking::class;
                $workable = OceanBooking::with(['carrier', 'vessel', 'pol', 'pod'])->find($workableId);
                
                if ($workable) {
                    $prefilledData = [
                        'booking_no'       => $workable->booking_no,
                        'carrier_id'       => $workable->carrier_id,
                        'carrier_name'     => $workable->carrier->name ?? '',
                        'carrier_bkg_no'   => $workable->booking_no,
                        'vessel_info'      => ($workable->vessel->name ?? '') . ($workable->voyage ? ' / ' . $workable->voyage : ''),
                        'place_of_receipt' => $workable->pol->name ?? '',
                        'etd'              => $workable->etd ? $workable->etd->format('Y-m-d') : '',
                    ];
                }
            } elseif ($workableType === 'App\Models\AirBooking' || $workableType === 'AirBooking') {
                $workableType = \App\Models\AirBooking::class;
                $workable = AirBooking::find($workableId);
                if ($workable) {
                    $prefilledData = [
                        'booking_no'       => $workable->booking_no,
                        'carrier_bkg_no'   => $workable->booking_no,
                        'etd'              => $workable->etd ? $workable->etd->format('Y-m-d') : '',
                    ];
                }
            }
        }

        // Generate work order number if none exists
        $workOrderNo = 'WO-' . date('Ymd') . '-' . rand(1000, 9999);

        $tradePartners = TradePartner::orderBy('name')->get();
        $ports = Port::orderBy('name')->get();
        $vessels = Vessel::orderBy('name')->get();

        return view('ocean-export.work-order-form', compact(
            'workableType',
            'workableId',
            'workable',
            'prefilledData',
            'workOrderNo',
            'tradePartners',
            'ports',
            'vessels'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_order_no'              => 'required|string|unique:work_orders,work_order_no',
            'workable_type'              => 'required|string',
            'workable_id'                => 'required|integer',
            'vendor_id'                  => 'nullable|exists:trade_partners,id',
            'issue_date'                 => 'nullable|date',
            'due_date'                   => 'nullable|date',
            'subject'                    => 'nullable|string|max:255',
            'instructions'               => 'nullable|string',
            'status'                     => 'required|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            
            'carrier_id'                 => 'nullable|exists:trade_partners,id',
            'carrier_bkg_no'             => 'nullable|string|max:255',
            'place_of_receipt'           => 'nullable|string|max:255',
            'vessel_info'                => 'nullable|string|max:255',
            'etd'                        => 'nullable|string|max:255',
            
            'empty_pickup_location_id'   => 'nullable|exists:trade_partners,id',
            'empty_pickup_address'       => 'nullable|string',
            'empty_pickup_ref'           => 'nullable|string|max:255',
            'empty_pickup_date'          => 'nullable|string|max:255',
            
            'freight_pickup_location_id' => 'nullable|exists:trade_partners,id',
            'freight_pickup_address'     => 'nullable|string',
            'freight_pickup_ref'         => 'nullable|string|max:255',
            'freight_pickup_date'        => 'nullable|string|max:255',
            
            'total_packages'             => 'nullable|integer',
            'package_unit'               => 'nullable|string|max:255',
            'container_qty'              => 'nullable|string|max:255',
            'gross_weight_kgs'           => 'nullable|string|max:255',
            'gross_weight_lbs'           => 'nullable|string|max:255',
            
            'show_bill_to'               => 'nullable|boolean',
            'bill_to_id'                 => 'nullable|exists:trade_partners,id',
            'bill_to_address'            => 'nullable|string',
            'bill_to_ref'                => 'nullable|string|max:255',
            
            'do_not_break_down_pallet'   => 'nullable|boolean',
            'extra_data'                 => 'nullable|array',
        ]);

        // Fix boolean inputs
        $validated['show_bill_to'] = $request->has('show_bill_to') || $request->input('show_bill_to') == '1';
        $validated['do_not_break_down_pallet'] = $request->has('do_not_break_down_pallet') || $request->input('do_not_break_down_pallet') == '1';
        $validated['created_by'] = auth()->id() ?? 1;

        DB::beginTransaction();
        try {
            $workOrder = WorkOrder::create($validated);
            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'id' => $workOrder->id]);
            }

            return redirect()->route('ocean-export.work-order.edit', $workOrder->id)
                ->with('success', 'Work Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Work Order Store Error: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withInput()->with('error', 'Failed to save Work Order: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workable = $workOrder->workable;
        $workableType = $workOrder->workable_type;
        $workableId = $workOrder->workable_id;

        $tradePartners = TradePartner::orderBy('name')->get();
        $ports = Port::orderBy('name')->get();
        $vessels = Vessel::orderBy('name')->get();

        return view('ocean-export.work-order-form', compact(
            'workOrder',
            'workableType',
            'workableId',
            'workable',
            'tradePartners',
            'ports',
            'vessels'
        ));
    }

    public function update(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        $validated = $request->validate([
            'work_order_no'              => 'required|string|unique:work_orders,work_order_no,' . $workOrder->id,
            'workable_type'              => 'required|string',
            'workable_id'                => 'required|integer',
            'vendor_id'                  => 'nullable|exists:trade_partners,id',
            'issue_date'                 => 'nullable|date',
            'due_date'                   => 'nullable|date',
            'subject'                    => 'nullable|string|max:255',
            'instructions'               => 'nullable|string',
            'status'                     => 'required|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            
            'carrier_id'                 => 'nullable|exists:trade_partners,id',
            'carrier_bkg_no'             => 'nullable|string|max:255',
            'place_of_receipt'           => 'nullable|string|max:255',
            'vessel_info'                => 'nullable|string|max:255',
            'etd'                        => 'nullable|string|max:255',
            
            'empty_pickup_location_id'   => 'nullable|exists:trade_partners,id',
            'empty_pickup_address'       => 'nullable|string',
            'empty_pickup_ref'           => 'nullable|string|max:255',
            'empty_pickup_date'          => 'nullable|string|max:255',
            
            'freight_pickup_location_id' => 'nullable|exists:trade_partners,id',
            'freight_pickup_address'     => 'nullable|string',
            'freight_pickup_ref'         => 'nullable|string|max:255',
            'freight_pickup_date'        => 'nullable|string|max:255',
            
            'total_packages'             => 'nullable|integer',
            'package_unit'               => 'nullable|string|max:255',
            'container_qty'              => 'nullable|string|max:255',
            'gross_weight_kgs'           => 'nullable|string|max:255',
            'gross_weight_lbs'           => 'nullable|string|max:255',
            
            'show_bill_to'               => 'nullable|boolean',
            'bill_to_id'                 => 'nullable|exists:trade_partners,id',
            'bill_to_address'            => 'nullable|string',
            'bill_to_ref'                => 'nullable|string|max:255',
            
            'do_not_break_down_pallet'   => 'nullable|boolean',
            'extra_data'                 => 'nullable|array',
        ]);

        $validated['show_bill_to'] = $request->has('show_bill_to') || $request->input('show_bill_to') == '1';
        $validated['do_not_break_down_pallet'] = $request->has('do_not_break_down_pallet') || $request->input('do_not_break_down_pallet') == '1';

        DB::beginTransaction();
        try {
            $workOrder->update($validated);
            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'id' => $workOrder->id]);
            }

            return redirect()->route('ocean-export.work-order.edit', $workOrder->id)
                ->with('success', 'Work Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Work Order Update Error: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withInput()->with('error', 'Failed to update Work Order: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->delete();
        return response()->json(['success' => true]);
    }
}
