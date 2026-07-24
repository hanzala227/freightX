<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTruckShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_no' => 'required|string|unique:truck_shipments,file_no',
            'mbl_no' => 'nullable|string|max:255',
            'hbl_no' => 'nullable|string|max:255',
            'vessel_flight_no' => 'nullable|string|max:255',
            'carrier_bkg_no' => 'nullable|string|max:255',
            'post_date' => 'nullable|date',
            'office_id' => 'nullable|exists:offices,id',
            'op_id' => 'nullable|exists:users,id',
            'sales_id' => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:trade_partners,id',
            'shipper_id' => 'nullable|exists:trade_partners,id',
            'consignee_id' => 'nullable|exists:trade_partners,id',
            'trucker_id' => 'nullable|exists:trade_partners,id',
            'bill_to_id' => 'nullable|exists:trade_partners,id',
            'customer_ref_no' => 'nullable|string|max:255',

            'truck_no' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:255',

            'ship_type' => 'nullable|string|max:255',
            'pol_id' => 'nullable|exists:ports,id',
            'pod_id' => 'nullable|exists:ports,id',
            'final_destination_id' => 'nullable|exists:ports,id',
            'etd' => 'nullable|date',
            'eta' => 'nullable|date',
            'feta' => 'nullable|date',

            'empty_pickup_location_id' => 'nullable|exists:trade_partners,id',
            'freight_pickup_location_id' => 'nullable|exists:trade_partners,id',
            'delivery_to_location_id' => 'nullable|exists:trade_partners,id',
            'empty_return_location_id' => 'nullable|exists:trade_partners,id',

            'pkg_qty' => 'nullable|numeric|min:0',
            'pkg_unit_id' => 'nullable|exists:package_units,id',
            'weight_kg' => 'nullable|numeric|min:0',
            'volume_cbm' => 'nullable|numeric|min:0',
            'measure_cft' => 'nullable|numeric|min:0',

            'est_delivery_date' => 'nullable|date',
            'is_delivered' => 'nullable|boolean',
            'delivered_date' => 'nullable|date',
            'is_ecommerce' => 'nullable|boolean',

            'quotation_id' => 'nullable|exists:quotations,id',

            'description' => 'nullable|string',
            'internal_remark' => 'nullable|string',
            'instruction_text' => 'nullable|string',

            'containers' => 'nullable|string',
            'memos' => 'nullable|string',
        ];
    }
}
