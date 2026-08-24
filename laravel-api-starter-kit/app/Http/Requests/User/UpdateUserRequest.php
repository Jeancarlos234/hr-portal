<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id ?? $this->route('id');

        return [
            'nombre' => ['sometimes', 'required', 'string', 'max:100'],
            'apellido' => ['sometimes', 'required', 'string', 'max:100'],
            'nombre_usuario' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:50',
                "unique:users,nombre_usuario,{$userId}",
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                "unique:users,email,{$userId}",
            ],
            'celular' => ['nullable', 'string', 'max:20'],
            'password' => [
                'sometimes',
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'is_active' => ['sometimes', 'boolean'],
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['exists:roles,slug'],
        ];
    }
}