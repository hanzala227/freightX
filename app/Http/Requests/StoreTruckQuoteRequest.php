<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTruckQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quote_no' => 'nullable|string|max:255|unique:quotations,quote_no',
            'quote_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',

            'customer_id' => 'nullable|exists:trade_partners,id',
            'sales_person_id' => 'nullable|exists:users,id',
            'office_id' => 'nullable|exists:offices,id',

            'transport_mode' => 'nullable|string|max:50',
            'pol_id' => 'nullable|exists:ports,id',
            'pod_id' => 'nullable|exists:ports,id',

            'incoterms_id' => 'nullable|string|max:255',
            'service_term' => 'nullable|string|max:255',

            'status' => 'nullable|string|in:DRAFT,SENT,ACCEPTED,REJECTED,EXPIRED',

            'commodity' => 'nullable|string|max:500',
            'pkg_qty' => 'nullable|numeric|min:0',
            'pkg_unit' => 'nullable|string|max:100',
            'weight_kg' => 'nullable|numeric|min:0',
            'weight_lb' => 'nullable|numeric|min:0',
            'volume_cbm' => 'nullable|numeric|min:0',
            'volume_cft' => 'nullable|numeric|min:0',

            'internal_remark' => 'nullable|string',
            'description' => 'nullable|string',

            'charges' => 'nullable|array',
            'charges.*.charge_code' => 'nullable|string|max:50',
            'charges.*.charge_name' => 'nullable|string|max:255',
            'charges.*.type' => 'nullable|string|in:AR,AP',
            'charges.*.amount' => 'nullable|numeric',
            'charges.*.qty' => 'nullable|numeric',
            'charges.*.rate' => 'nullable|numeric',
            'charges.*.unit' => 'nullable|string|max:50',
            'charges.*.currency_id' => 'nullable|exists:currencies,id',
            'charges.*.remark' => 'nullable|string',

            'documents' => 'nullable|array',
            'documents.*.file_name' => 'nullable|string|max:255',
            'documents.*.file_size' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'quote_no.unique' => 'This Quote No. is already taken.',
            'customer_id.exists' => 'Selected customer is invalid.',
            'sales_person_id.exists' => 'Selected sales person is invalid.',
            'pol_id.exists' => 'Selected port of loading is invalid.',
            'pod_id.exists' => 'Selected port of discharge is invalid.',
            'charges.*.currency_id.exists' => 'Selected currency is invalid.',
        ];
    }
}
