<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer');
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'middle_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('customers', 'phone')->ignore($customerId),
            ],
            'phone2' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('customers', 'phone2')->ignore($customerId),
            ],
            'status' => 'sometimes|integer',

            'customer_detail' => 'nullable|array',
            'customer_detail.region_id' => 'nullable|integer|exists:regions,id',
            'customer_detail.city_id' => 'nullable|integer|exists:cities,id',
            'customer_detail.district_id' => 'nullable|integer|exists:districts,id',
            'customer_detail.neighborhood_id' => 'nullable|integer|exists:neighborhoods,id',
            'customer_detail.home' => 'nullable|string|max:255',
        ];
    }
}
