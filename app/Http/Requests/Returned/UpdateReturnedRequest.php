<?php

namespace App\Http\Requests\Returned;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReturnedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|exists:customers,id',
            'quantity' => 'sometimes|integer',
        ];
    }
}
