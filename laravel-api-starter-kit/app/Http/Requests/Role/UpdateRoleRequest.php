<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('role')?->id ?? $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100', "unique:roles,name,{$roleId}"],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                "unique:roles,slug,{$roleId}",
                'regex:/^[a-z0-9-]+$/',
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['exists:permissions,slug'],
        ];
    }
}