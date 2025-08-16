<?php

namespace App\Http\Requests\Settings\Role;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name'
        ];
    }
}
