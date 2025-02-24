<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'sometimes|exists:companies,id',
            'city_id' => 'sometimes|exists:cities,id',
            'district_id' => 'sometimes|exists:districts,id',
            'neighborhood_id' => 'sometimes|exists:neighborhoods,id',
            'quantity' => 'sometimes|integer|min:1',
            'sum' => 'sometimes|numeric|min:0',
            'date' => 'sometimes|date',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'status' => 'sometimes|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.exists' => 'Berilgan kompaniya mavjud emas.',

            'city_id.exists' => 'Berilgan shahar mavjud emas.',

            'district_id.exists' => 'Berilgan tuman mavjud emas.',

            'neighborhood_id.exists' => 'Berilgan mahalla mavjud emas.',

            'quantity.integer' => 'Miqdor son bo\'lishi kerak.',
            'quantity.min' => 'Miqdor kamida 1 bo\'lishi kerak.',

            'sum.numeric' => 'Summa raqam bo\'lishi kerak.',
            'sum.min' => 'Summa manfiy bo\'lishi mumkin emas.',

            'date.date' => 'Sana noto\'g\'ri formatda.',

            'address.string' => 'Manzil faqat matn bo\'lishi kerak.',
            'address.max' => 'Manzil uzunligi 255 belgidan oshmasligi kerak.',

            'note.string' => 'Izoh faqat matn bo\'lishi kerak.',
            'note.max' => 'Izoh uzunligi 500 belgidan oshmasligi kerak.',

            'latitude.string' => 'Kenglik faqat string bo\'lishi kerak.',

            'longitude.string' => 'Uzunlik faqat string bo\'lishi kerak.',

            'status.integer' => 'Holat faqat butun son bo\'lishi kerak.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validatsiya xatosi',
                'errors' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
