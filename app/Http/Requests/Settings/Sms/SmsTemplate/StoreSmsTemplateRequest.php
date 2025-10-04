<?php

namespace App\Http\Requests\Settings\Sms\SmsTemplate;

use App\Enums\Sms\SmsTemplateTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSmsTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:1000'],
            'type' => ['required', Rule::in(SmsTemplateTypeEnum::values())],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Shablon nomi majburiy',
            'content.required' => 'Shablon matni majburiy',
            'type.required' => 'Shablon turi majburiy',
            'type.in' => 'Noto\'g\'ri shablon turi',
        ];
    }
}
