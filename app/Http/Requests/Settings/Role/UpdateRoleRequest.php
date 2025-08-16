<?php

namespace App\Http\Requests\Settings\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->route('role'))
            ],
            'guard_name' => 'sometimes|string|max:255',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Role with this name already exists',
            'permissions.array' => 'Permissions must be an array',
            'permissions.*.exists' => 'One or more permissions are invalid',
        ];
    }
}
