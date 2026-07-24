<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receipt_no' => 'required|string|max:255|unique:warehouse_receipts,receipt_no',
            'receipt_date' => 'required|date',
            'warehouse_id' => 'nullable|exists:trade_partners,id',
            'customer_id' => 'nullable|exists:trade_partners,id',
            'shipper_id' => 'nullable|exists:trade_partners,id',
            'consignee_id' => 'nullable|exists:trade_partners,id',
            'office_id' => 'required|exists:offices,id',
            'operator_id' => 'nullable|exists:users,id',
            'tracking_no' => 'nullable|string|max:255',
            'carrier_name' => 'nullable|string|max:255',
            'cargo_type' => 'nullable|string|max:10',
            'is_hazardous' => 'nullable|boolean',
            'is_heat_treated' => 'nullable|boolean',
            'commodity' => 'nullable|string|max:500',
            'po_no' => 'nullable|string|max:255',
            'location_code' => 'nullable|string|max:50',
            'delivered_by' => 'nullable|string|max:100',
            'freight_charge_type' => 'nullable|string|max:20',
            'freight_amount' => 'nullable|numeric|min:0',
            'check_no' => 'nullable|string|max:100',
            'internal_remark' => 'nullable|string',
        ];
    }
}
