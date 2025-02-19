<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'quantity' => 'required|integer|min:1',
            'sum' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'status' => 'nullable|boolean',
            'source_id' => 'nullable|integer|exists:sources,id',
        ];
    }
}
