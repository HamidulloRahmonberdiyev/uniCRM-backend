<?php

namespace App\Http\Requests\Neighborhood;

use Illuminate\Foundation\Http\FormRequest;

class FilterNeighborhoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'district_id' => 'nullable|integer|exists:districts,id',
            'name' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];
    }
}
