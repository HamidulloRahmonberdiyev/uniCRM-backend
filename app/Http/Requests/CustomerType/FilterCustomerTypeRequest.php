<?php

namespace App\Http\Requests\CustomerType;

use Illuminate\Foundation\Http\FormRequest;

class FilterCustomerTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id' => ['required', 'integer', 'exists:sms_templates,id'],
            'customer_type_id' => ['nullable', 'integer', 'exists:customer_types,id'],
            'limit' => ['nullable', 'integer', 'max:1000'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'neighborhood_id' => ['nullable', 'integer', 'exists:neighborhoods,id'],
        ];
    }
}
