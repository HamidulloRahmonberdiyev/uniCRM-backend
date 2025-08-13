<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeOrderActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                Rule::exists('users', 'id')
            ],
            'status' => [
                'required',
                'string',
                Rule::in(OrderStatusEnum::getValidStrings())
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.exists' => 'The selected supplier is not valid or not active.',
            'status.in' => 'The status must be one of: ' . implode(', ', OrderStatusEnum::getValidStrings()),
        ];
    }

    public function getStatusEnum(): OrderStatusEnum
    {
        return OrderStatusEnum::fromString($this->validated('status'));
    }
}
