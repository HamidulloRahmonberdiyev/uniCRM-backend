<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class QuickStoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge(['phone' => sanitizePhone($this->phone)]);
        }
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:17',
            'first_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|integer|exists:products,id',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Telefon raqami majburiy.',
            'phone.max' => 'Telefon raqami 17 belgidan oshmasligi kerak.',

            'first_name.required' => 'Mijoz ismini kiriting.',
            'first_name.max' => 'Ism 255 belgidan oshmasligi kerak.',

            'address.required' => 'Manzil kiritish majburiy.',
            'address.max' => 'Manzil uzunligi 255 belgidan oshmasligi kerak.',

            'quantity.required' => 'Miqdor kiritish majburiy.',
            'quantity.integer' => 'Miqdor son bo\'lishi kerak.',
            'quantity.min' => 'Miqdor kamida 1 bo\'lishi kerak.',

            'product_id.required' => 'Mahsulotni tanlash majburiy.',
            'product_id.exists' => 'Berilgan mahsulot mavjud emas.',

            'price.required' => 'Narx kiritish majburiy.',
            'price.numeric' => 'Narx raqam bo\'lishi kerak.',
            'price.min' => 'Narx manfiy bo\'lishi mumkin emas.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = collect($validator->errors())->map(fn ($messages) => $messages[0]);

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validatsiya xatosi',
                'errors' => $errors,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
