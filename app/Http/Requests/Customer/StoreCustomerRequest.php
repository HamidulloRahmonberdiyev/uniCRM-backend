<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer|exists:users,id',
            'company_id' => 'nullable|integer|exists:companies,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'phone' => 'required|string|max:15|unique:customers,phone',
            'phone2' => 'nullable|string|max:15|unique:customers,phone2',
            'status' => 'nullable|integer',

            'customer_detail' => 'nullable|array',
            'customer_detail.region_id' => 'nullable|integer|exists:regions,id',
            'customer_detail.city_id' => 'nullable|integer|exists:cities,id',
            'customer_detail.district_id' => 'nullable|integer|exists:districts,id',
            'customer_detail.neighborhood_id' => 'nullable|integer|exists:neighborhoods,id',
            'customer_detail.home' => 'nullable|string|max:255',
        ];
    }
}
