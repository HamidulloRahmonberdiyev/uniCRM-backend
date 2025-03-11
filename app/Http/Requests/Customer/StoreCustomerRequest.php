<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => sanitizePhone($this->phone),
            ]);
        }

        if ($this->has('phone2')) {
            $this->merge([
                'phone2' => sanitizePhone($this->phone2),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer|exists:users,id',
            'company_id' => 'nullable|integer|exists:companies,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'phone' => 'required|string|max:17|unique:customers,phone,phone2',
            'phone2' => 'nullable|string|max:17|unique:customers,phone,phone2',
            'status' => 'nullable|integer',

            'customer_detail' => 'nullable|array',
            'customer_detail.region_id' => 'nullable|integer|exists:regions,id',
            'customer_detail.district_id' => 'nullable|integer|exists:districts,id',
            'customer_detail.neighborhood_id' => 'nullable|integer|exists:neighborhoods,id',
            'customer_detail.home' => 'nullable|string|max:255',
            'customer_detail.bottle_count' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'Foydalanuvchi topilmadi.',

            'company_id.exists' => 'Kompaniya topilmadi.',

            'first_name.required' => 'Mijoz ismini kiriting.',
            'first_name.string' => 'Ism faqat matn bo\'lishi kerak.',
            'first_name.max' => 'Ism 255 belgidan oshmasligi kerak.',

            'last_name.string' => 'Familiya faqat matn bo\'lishi kerak.',
            'last_name.max' => 'Familiya 255 belgidan oshmasligi kerak.',

            'middle_name.string' => 'Otasining ismi faqat matn bo\'lishi kerak.',
            'middle_name.max' => 'Otasining ismi 255 belgidan oshmasligi kerak.',

            'date_of_birth.date' => 'Tug\'ilgan sana to\'g\'ri formatda bo\'lishi kerak.',

            'phone.required' => 'Telefon raqami majburiy.',
            'phone.string' => 'Telefon raqami faqat matn bo\'lishi kerak.',
            'phone.max' => 'Telefon raqami 17 belgidan oshmasligi kerak.',
            'phone.unique' => 'Bu telefon raqami allaqachon ro\'yxatdan o\'tgan.',

            'phone2.string' => 'Ikkinchi telefon raqami faqat matn bo\'lishi kerak.',
            'phone2.max' => 'Ikkinchi telefon raqami 17 belgidan oshmasligi kerak.',
            'phone2.unique' => 'Bu telefon raqami allaqachon ro\'yxatdan o\'tgan.',

            'status.integer' => 'Holat faqat butun son bo\'lishi kerak.',

            'customer_detail.array' => 'Mijoz tafsilotlari massiv bo\'lishi kerak.',

            'customer_detail.region_id.exists' => 'Viloyat topilmadi.',

            'customer_detail.district_id.exists' => 'Tuman topilmadi.',

            'customer_detail.neighborhood_id.exists' => 'Mahalla topilmadi.',

            'customer_detail.home.string' => 'Uy manzili faqat matn bo\'lishi kerak.',
            'customer_detail.home.max' => 'Uy manzili 255 belgidan oshmasligi kerak.',

            'customer_detail.bottle_count.integer' => 'Idish soni faqat butun son bo\'lishi kerak.',
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
