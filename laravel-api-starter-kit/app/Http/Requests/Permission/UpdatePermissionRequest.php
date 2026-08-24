<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
        $permissionId = $this->route('permission')?->id ?? $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100', "unique:permissions,name,{$permissionId}"],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                "unique:permissions,slug,{$permissionId}",
                'regex:/^[a-z0-9.-]+$/',
            ],
            'module' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}