<?php

namespace App\Http\Requests\Neighborhood;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNeighborhoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'district_id' => 'required|integer|exists:districts,id',
            'name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ];
    }
}
