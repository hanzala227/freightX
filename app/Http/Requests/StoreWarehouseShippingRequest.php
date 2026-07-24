<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseShippingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_no' => 'required|string|max:255|unique:warehouse_shippings,shipping_no',
            'shipping_date' => 'required|date',
            'office_id' => 'required|exists:offices,id',
            'warehouse_id' => 'required|exists:trade_partners,id',
            'customer_id' => 'required|exists:trade_partners,id',
            'bill_to_id' => 'nullable|exists:trade_partners,id',
            'ship_to' => 'nullable|exists:trade_partners,id',
            'trucker_id' => 'nullable|exists:trade_partners,id',
            'op_id' => 'nullable|exists:users,id',
            'quotation_no' => 'nullable|string|max:255',
            'order_no' => 'nullable|string|max:255',
            'truck_bl_no' => 'nullable|string|max:255',
            'order_date' => 'nullable|date',
            'out_date' => 'required|date',
            'pallet' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'internal_remark' => 'nullable|string',
        ];
    }
}
