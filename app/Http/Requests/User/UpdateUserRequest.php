<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'nullable',
                'string',
                Rule::unique('users')->ignore($user->id)->where(function ($query) {
                    return $query->where('email', $this->email);
                }),
            ],
            'username' => [
                'sometimes',
                'nullable',
                'string',
                'max:17',
                Rule::unique('users')->ignore($user->id)->where(function ($query) {
                    return $query->where('username', $this->username);
                }),
            ],
            'phone' => [
                'sometimes',
                'string',
                'max:17',
                Rule::unique('users')->ignore($user->id)->where(function ($query) {
                    return $query->where('phone', $this->phone);
                }),
            ],
            'password' => 'sometimes|string|min:8',
            'roles' => 'sometimes|nullable|array',
            'roles.*' => 'exists:roles,id',
            'status' => 'sometimes|nullable|boolean',
        ];
    }
}
