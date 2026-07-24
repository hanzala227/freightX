<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTradePartnerCreditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'credit_limit' => 'nullable|numeric|min:0|max:9999999999.99',
            'available_credit' => 'nullable|numeric|min:0',
            'is_blocked' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'credit_limit.numeric' => 'Credit limit must be a valid number.',
            'is_blocked.boolean' => 'Blocked status must be true or false.',
        ];
    }
}
