<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseReceivingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_receipt_id' => 'required|exists:warehouse_receipts,id',
            'office_id' => 'nullable|exists:offices,id',
            'customer_id' => 'nullable|exists:trade_partners,id',
            'bill_to_id' => 'nullable|exists:trade_partners,id',
            'ship_from_id' => 'nullable|exists:trade_partners,id',
            'quotation_no' => 'nullable|string|max:100',
            'bl_no' => 'nullable|string|max:100',
            'trucker_id' => 'nullable|exists:trade_partners,id',
            'container_no' => 'nullable|string|max:100',
            'receiving_date' => 'nullable|date',
            'post_date' => 'nullable|date',
            'order_date' => 'nullable|date',
            'expect_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'pallet' => 'nullable|string|max:100',
            'operator_id' => 'nullable|exists:users,id',
            'internal_remark' => 'nullable|string',
        ];
    }
}
