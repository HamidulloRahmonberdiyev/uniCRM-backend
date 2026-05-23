<?php

namespace App\Http\Requests\Settings\Sms\Eskiz;

use Illuminate\Foundation\Http\FormRequest;

class EskizSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_ids' => ['required', 'array'],
            'template_id' => ['required', 'integer', 'exists:sms_templates,id'],
            'order_id' => ['nullable', 'integer', 'exists:sms_templates,id'],
        ];
    }
}
