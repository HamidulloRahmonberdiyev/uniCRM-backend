<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class FilterLocatedOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit' => 'required|integer|min:1|max:500',
            'supplier_id' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|integer',
        ];
    }
}
