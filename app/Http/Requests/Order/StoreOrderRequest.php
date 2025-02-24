<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'quantity' => 'required|integer|min:1',
            'sum' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'status' => 'nullable|boolean',
            'source_id' => 'nullable|integer|exists:sources,id',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Mijozni tanlash majburiy.',
            'customer_id.exists' => 'Berilgan mijoz mavjud emas.',

            'user_id.exists' => 'Berilgan foydalanuvchi mavjud emas.',

            'company_id.exists' => 'Berilgan kompaniya mavjud emas.',

            'city_id.exists' => 'Berilgan shahar mavjud emas.',

            'district_id.exists' => 'Berilgan tuman mavjud emas.',

            'neighborhood_id.exists' => 'Berilgan mahalla mavjud emas.',

            'quantity.required' => 'Miqdor kiritish majburiy.',
            'quantity.integer' => 'Miqdor son bo\'lishi kerak.',
            'quantity.min' => 'Miqdor kamida 1 bo\'lishi kerak.',

            'sum.numeric' => 'Summa raqam bo\'lishi kerak.',
            'sum.min' => 'Summa manfiy bo\'lishi mumkin emas.',

            'date.date' => 'Sana noto\'g\'ri formatda.',

            'address.string' => 'Manzil faqat matn bo\'lishi kerak.',
            'address.max' => 'Manzil uzunligi 255 belgidan oshmasligi kerak.',

            'note.string' => 'Izoh faqat matn bo\'lishi kerak.',
            'note.max' => 'Izoh uzunligi 500 belgidan oshmasligi kerak.',

            'latitude.string' => 'Kenglik faqat matn bo\'lishi kerak.',

            'longitude.string' => 'Uzunlik faqat matn bo\'lishi kerak.',

            'status.boolean' => 'Holat faqat boolean (ha yoki yo\'q) bo\'lishi kerak.',

            'source_id.exists' => 'Berilgan manba mavjud emas.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $formattedErrors = [];
        foreach ($errors as $field => $messages) {
            $formattedErrors[$field] = $messages[0];
        }

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validatsiya xatosi',
                'errors' => $formattedErrors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
