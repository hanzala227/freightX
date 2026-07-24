<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:trade_partners,id',
            'customer_id' => 'required|exists:trade_partners,id',
            'vendor_id' => 'nullable|exists:trade_partners,id',
            'sku' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'upc_ean' => 'nullable|string|max:100',
            'mpn' => 'nullable|string|max:100',
            'hts_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'inner_pack' => 'nullable|numeric|min:0',
            'on_hand_qty' => 'nullable|numeric|min:0',
            'available_qty' => 'nullable|numeric|min:0',
            'unit_id' => 'nullable|exists:package_units,id',
            'weight_kg' => 'nullable|numeric|min:0',
            'volume_cbm' => 'nullable|numeric|min:0',
            'dimension_length' => 'nullable|numeric|min:0',
            'dimension_width' => 'nullable|numeric|min:0',
            'dimension_height' => 'nullable|numeric|min:0',
            'dimension_unit' => 'nullable|string|in:cm,inch,feet',
            'remark' => 'nullable|string',
            'create_date' => 'nullable|date',
            'status' => 'nullable|string|in:enable,disable',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
