<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'status' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'The file must be a valid image',
            'image.mimes' => 'Only JPEG, PNG, JPG, GIF, and WEBP formats are allowed',
            'image.max' => 'Image size cannot exceed 2MB',
        ];
    }
}
