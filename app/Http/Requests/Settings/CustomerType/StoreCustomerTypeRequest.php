<?php

namespace App\Http\Requests\Settings\CustomerType;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => 'required|string|max:100',
            'number' => 'required|integer',
            'color' => 'nullable|string|max:100',
            'sortable' => 'nullable|integer',
        ];
    }
}
