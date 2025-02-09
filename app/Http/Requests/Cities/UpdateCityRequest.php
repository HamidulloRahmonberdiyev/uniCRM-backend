<?php

namespace App\Http\Requests\Cities;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'region_id' => 'required|integer|exists:regions,id',
            'name' => 'required|max:255|string',
            'status' => 'nullable|boolean',
        ];
    }
}
