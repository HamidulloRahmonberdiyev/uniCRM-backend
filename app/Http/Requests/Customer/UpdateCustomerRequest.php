<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'middle_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'phone' => 'sometimes|string|max:15|unique:customers,phone,' . $this->route('customer'),
            'phone2' => 'sometimes|string|max:15|unique:customers,phone2,' . $this->route('customer'),
            'status' => 'sometimes|boolean',
        ];
    }
}
