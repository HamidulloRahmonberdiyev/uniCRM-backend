<?php

namespace App\Http\Requests\Settings\Sms\SmsTemplate;

use App\Enums\Sms\SmsTemplateTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSmsTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string', 'max:1000'],
            'type' => ['sometimes', Rule::in(SmsTemplateTypeEnum::values())],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Shablon nomi matn bo\'lishi kerak',
            'content.string' => 'Shablon matni matn bo\'lishi kerak',
            'type.in' => 'Noto\'g\'ri shablon turi',
        ];
    }
}
