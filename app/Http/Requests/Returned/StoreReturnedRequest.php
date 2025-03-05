<?php

namespace App\Http\Requests\Returned;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer',
        ];
    }
}
