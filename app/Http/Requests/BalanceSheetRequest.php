<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BalanceSheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'as_of_date' => 'nullable|date',
            'office_id'  => 'nullable|exists:offices,id',
            'currency'   => 'nullable|in:original,converted',
        ];
    }

    public function messages(): array
    {
        return [
            'office_id.exists' => 'Selected office does not exist.',
        ];
    }
}
