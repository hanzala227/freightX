<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTradePartnerRequest extends FormRequest
{
     public function authorize(): bool
     {
         return true;
     }

     public function prepareForValidation(): void
     {
         $integerFields = [
             'country_id', 'sales_office_id', 'sales_person_id', 'cs_person_id',
             'credit_term_days', 'bank_currency_1_id', 'bank_currency_2_id',
             'account_group_id', 'credit_limit_group_id',
         ];
         $numericFields = ['credit_limit', 'profit_share_percent'];

         foreach ($integerFields as $field) {
             if ($this->has($field) && $this->input($field) === '') {
                 $this->merge([$field => null]);
             }
         }
         foreach ($numericFields as $field) {
             if ($this->has($field) && $this->input($field) === '') {
                 $this->merge([$field => null]);
             }
         }
     }
 
     public function rules(): array
     {
         return [
             'type' => 'required|string',
             'name' => 'required|string|max:255',
             'print_name' => 'required|string|max:255',
             'country_id' => 'required|integer',
             'alias' => 'nullable|string',
             'local_name' => 'nullable|string',
             'local_address' => 'nullable|string',
             'city' => 'nullable|string',
             'state' => 'nullable|string',
             'zip_code' => 'nullable|string',
             'iata_code' => 'nullable|string',
             'corporation_no' => 'nullable|string',
             'sita_profile' => 'nullable|string',
             'account_no' => 'nullable|string',
             'scac_code' => 'nullable|string',
             'firms_code' => 'nullable|string',
             'cbsa_carrier_code' => 'nullable|string',
             'phone' => 'nullable|string',
             'fax' => 'nullable|string',
             'url' => 'nullable|string',
             'email' => 'nullable|email|max:255',
             'status' => 'nullable|string|in:BUSINESS,PRE_BUSINESS,INACTIVE',
             'sales_office_id' => 'nullable|integer',
             'sales_person_id' => 'nullable|integer',
             'cs_person_id' => 'nullable|integer',
             'billing_address' => 'nullable|string',
             'tax_id' => 'nullable|string',
             'payment_type' => 'nullable|string',
             'track_1099' => 'nullable|boolean',
             'bill_to_agent' => 'nullable|boolean',
             'clm_id' => 'nullable|string|max:255',
             'credit_term_days' => 'nullable|integer',
             'credit_limit' => 'nullable|numeric',
             'accountant_name' => 'nullable|string',
             'bank_account_name_1' => 'nullable|string',
             'bank_account_no_1' => 'nullable|string',
             'bank_currency_1_id' => 'nullable|integer',
             'bank_account_name_2' => 'nullable|string',
             'bank_account_no_2' => 'nullable|string',
             'bank_currency_2_id' => 'nullable|integer',
             'profit_share_percent' => 'nullable|numeric',
             'popup_tips' => 'nullable|array',
             'remark' => 'nullable|string',
             
             // Relationships inputs
             'contacts' => 'nullable|array',
             'memos' => 'nullable|array',
             'defaultFreights' => 'nullable|array',
             'commodities' => 'nullable|array',
             'filingSetting' => 'nullable|array',
             'relatedParties' => 'nullable|array',

             // New fields
             'account_group_id' => ['nullable', 'integer', 'exists:account_groups,id'],
             'credit_limit_group_id' => ['nullable', 'integer', 'exists:credit_limit_groups,id'],
             'aeo' => ['nullable', 'string', 'max:255'],
             'credit_term_unit' => ['nullable', 'string', 'in:Days,Months,Years'],
             'print_address_use_default' => ['nullable', 'boolean'],
             'print_address_show_name' => ['nullable', 'boolean'],
             'print_address_show_address' => ['nullable', 'boolean'],
             'print_address_show_contact' => ['nullable', 'boolean'],
             'additional_addresses' => ['nullable', 'array'],
         ];
     }
}
