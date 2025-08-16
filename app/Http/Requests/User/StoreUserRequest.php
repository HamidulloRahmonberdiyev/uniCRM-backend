<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'status' => 'nullable|boolean',
        ];
    }
}
