<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer');
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'middle_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'phone' => [
                'required',
                'string',
                'max:17',
                Rule::unique('customers', 'phone')->ignore($customerId),
            ],
            'phone2' => [
                'nullable',
                'string',
                'max:17',
                Rule::unique('customers', 'phone2')->ignore($customerId),
            ],
            'status' => 'sometimes|integer',

            'customer_detail' => 'nullable|array',
            'customer_detail.region_id' => 'nullable|integer|exists:regions,id',
            'customer_detail.city_id' => 'nullable|integer|exists:cities,id',
            'customer_detail.district_id' => 'nullable|integer|exists:districts,id',
            'customer_detail.neighborhood_id' => 'nullable|integer|exists:neighborhoods,id',
            'customer_detail.home' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.sometimes' => 'Ismni kiritish majburiy emas, lekin agar kiritilsa, u faqat string va 255 ta belgidan kam bo\'lishi kerak.',
            'first_name.string' => 'Ism faqat matn bo\'lishi kerak.',
            'first_name.max' => 'Ismning uzunligi 255 belgidan oshmasligi kerak.',

            'last_name.sometimes' => 'Familiya kiritish majburiy emas, lekin agar kiritilsa, u faqat string va 255 ta belgidan kam bo\'lishi kerak.',
            'last_name.string' => 'Familiya faqat matn bo\'lishi kerak.',
            'last_name.max' => 'Familiyaning uzunligi 255 belgidan oshmasligi kerak.',

            'middle_name.sometimes' => 'Otasining ismi kiritish majburiy emas, lekin agar kiritilsa, u faqat string va 255 ta belgidan kam bo\'lishi kerak.',
            'middle_name.string' => 'Otasining ismi faqat matn bo\'lishi kerak.',
            'middle_name.max' => 'Otasining ismining uzunligi 255 belgidan oshmasligi kerak.',

            'date_of_birth.sometimes' => 'Tug\'ilgan sanani kiritish majburiy emas, lekin agar kiritilsa, u sana formatida bo\'lishi kerak.',
            'date_of_birth.date' => 'Tug\'ilgan sana noto\'g\'ri formatda.',

            'phone.required' => 'Telefon raqami kiritilishi kerak.',
            'phone.string' => 'Telefon raqami faqat matn bo\'lishi kerak.',
            'phone.max' => 'Telefon raqami uzunligi 17 belgidan oshmasligi kerak.',
            'phone.unique' => 'Bu telefon raqami allaqachon ro\'yxatga olingan.',

            'phone2.string' => 'Ikkinchi telefon raqami faqat matn bo\'lishi kerak.',
            'phone2.max' => 'Ikkinchi telefon raqami uzunligi 17 belgidan oshmasligi kerak.',
            'phone2.unique' => 'Bu ikkinchi telefon raqami allaqachon ro\'yxatga olingan.',

            'status.sometimes' => 'Holatni kiritish majburiy emas, lekin agar kiritilsa, u faqat son bo\'lishi kerak.',
            'status.integer' => 'Holat faqat son bo\'lishi kerak.',

            'customer_detail.array' => 'Mijozning ma\'lumotlari to\'g\'ri formatda bo\'lishi kerak.',

            'customer_detail.region_id.exists' => 'Berilgan hudud mavjud emas.',

            'customer_detail.city_id.exists' => 'Berilgan shahar mavjud emas.',

            'customer_detail.district_id.exists' => 'Berilgan tuman mavjud emas.',

            'customer_detail.neighborhood_id.exists' => 'Berilgan mahalla mavjud emas.',

            'customer_detail.home.string' => 'Uy manzili faqat matn bo\'lishi kerak.',
            'customer_detail.home.max' => 'Uy manzilining uzunligi 255 belgidan oshmasligi kerak.',
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
