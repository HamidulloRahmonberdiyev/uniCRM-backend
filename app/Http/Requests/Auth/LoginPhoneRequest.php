<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginPhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
                'regex:/^[\+]?[0-9\-\(\)\s]+$/'
            ],
            'password' => [
                'required',
                'string',
                'min:5'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Telefon raqami majburiy.',
            'phone.regex' => 'Telefon raqami formati noto\'g\'ri.',
            'password.required' => 'Parol majburiy.',
            'password.min' => 'Parol kamida 6 ta belgidan iborat bo\'lishi kerak.'
        ];
    }
}
