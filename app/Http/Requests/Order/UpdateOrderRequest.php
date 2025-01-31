<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|exists:customers,id',
            'user_id' => 'sometimes|exists:users,id',
            'company_id' => 'sometimes|exists:companies,id',
            'city_id' => 'sometimes|exists:cities,id',
            'district_id' => 'sometimes|exists:districts,id',
            'neighborhood_id' => 'sometimes|exists:neighborhoods,id',
            'quantity' => 'sometimes|integer|min:1',
            'sum' => 'sometimes|numeric|min:0',
            'date' => 'sometimes|date',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
            'location' => 'nullable|string',
            'status' => 'sometimes|boolean',
        ];
    }
}
