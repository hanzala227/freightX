<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAirExportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_ecommerce' => filter_var($this->is_ecommerce, FILTER_VALIDATE_BOOLEAN),
            'is_blocked' => filter_var($this->is_blocked, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules()
    {
        return [
            'file_no' => 'required|string|unique:air_exports,file_no',
            'mawb_no' => 'nullable|string',
            'booking_no' => 'nullable|string',
            'post_date' => 'nullable|date',
            'office_id' => 'nullable|exists:offices,id',
            'op_id' => 'nullable|exists:users,id',
            'forwarding_agent_id' => 'nullable|exists:trade_partners,id',
            'oversea_agent_id' => 'nullable|exists:trade_partners,id',
            'carrier_id' => 'nullable|exists:trade_partners,id',
            'acct_carrier_id' => 'nullable|exists:trade_partners,id',

            'flight_no' => 'nullable|string',
            'dep_port_id' => 'nullable|exists:ports,id',
            'dst_port_id' => 'nullable|exists:ports,id',
            'etd' => 'nullable|date',
            'eta' => 'nullable|date',
            'atd' => 'nullable|date',
            'ata' => 'nullable|date',

            'pkg_qty' => 'nullable|numeric',
            'pkg_unit_id' => 'nullable|exists:package_units,id',
            'gross_weight' => 'nullable|numeric',
            'chargeable_weight' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
            'buying_rate' => 'nullable|numeric',
            'selling_rate' => 'nullable|numeric',

            'freight_term' => 'nullable|string',
            'is_ecommerce' => 'boolean',
            'sales_type' => 'nullable|string',
            'is_blocked' => 'boolean',
            'is_direct_master' => 'boolean',
            'internal_remark' => 'nullable|string',

            // Direct Master fields
            'agent_ref_no' => 'nullable|string',
            'dm_customer_id' => 'nullable|exists:trade_partners,id',
            'dm_shipper_id' => 'nullable|exists:trade_partners,id',
            'dm_bill_to_id' => 'nullable|exists:trade_partners,id',
            'dm_consignee_id' => 'nullable|exists:trade_partners,id',
            'dm_notify_id' => 'nullable|exists:trade_partners,id',

            // MAWB party fields
            'shipper_id' => 'nullable|exists:trade_partners,id',
            'consignee_id' => 'nullable|exists:trade_partners,id',
            'notify_id' => 'nullable|exists:trade_partners,id',
            'actual_shipper_id' => 'nullable|exists:trade_partners,id',

            'hbls' => 'nullable|array',
            'hbls.*.hawb_no' => 'nullable|string',
            'hbls.*.customer_id' => 'nullable|exists:trade_partners,id',
            'hbls.*.shipper_id' => 'nullable|exists:trade_partners,id',
            'hbls.*.consignee_id' => 'nullable|exists:trade_partners,id',
            'hbls.*.notify_party_id' => 'nullable|exists:trade_partners,id',
            'hbls.*.sales_person_id' => 'nullable|exists:users,id',
            'hbls.*.pkg_qty' => 'nullable|numeric',
            'hbls.*.pkg_unit_id' => 'nullable|exists:package_units,id',
            'hbls.*.gross_weight' => 'nullable|numeric',
            'hbls.*.chargeable_weight' => 'nullable|numeric',
            'hbls.*.volume' => 'nullable|numeric',
            'hbls.*.buying_rate' => 'nullable|numeric',
            'hbls.*.selling_rate' => 'nullable|numeric',
            'hbls.*.commodity' => 'nullable|string',
            'hbls.*.incoterms_id' => 'nullable|string',
            'hbls.*.freight_term' => 'nullable|string',
            'hbls.*.sales_type' => 'nullable|string',
            'hbls.*.hbl_remark' => 'nullable|string',
            'hbls.*.booking_date' => 'nullable|date',
            'hbls.*.itn_no' => 'nullable|string',
            'hbls.*.departure' => 'nullable|string',
            'hbls.*.destination' => 'nullable|string',
            'hbls.*.feta' => 'nullable|date',
            'hbls.*.cargo_type' => 'nullable|string',
            'hbls.*.ship_type' => 'nullable|string',
            'hbls.*.display_unit' => 'nullable|string',
            'hbls.*.bill_to' => 'nullable|string',
            'hbls.*.cargo_pickup' => 'nullable|string',
            'hbls.*.delivery_to' => 'nullable|string',
            'hbls.*.oversea_agent_id' => 'nullable|exists:trade_partners,id',
            'hbls.*.mark' => 'nullable|string',
            'hbls.*.description' => 'nullable|string',
            'hbls.*.remark' => 'nullable|string',

            'charges' => 'nullable|array',
            'charges.*.charge_code' => 'nullable|string',
            'charges.*.description' => 'nullable|string',
            'charges.*.term' => 'nullable|string',
            'charges.*.rate' => 'nullable|numeric',
            'charges.*.amount' => 'nullable|numeric',
            'charges.*.currency' => 'nullable|string',
            'charges.*.type' => 'nullable|string|in:origin_revenue,destination_revenue,origin_cost',
        ];
    }
}
