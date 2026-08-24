<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
        return [
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'nombre_usuario' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:users,nombre_usuario',
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'celular' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'The first name field is required.',
            'nombre.regex' => 'The first name may only contain letters and spaces.',
            'apellido.required' => 'The last name field is required.',
            'apellido.regex' => 'The last name may only contain letters and spaces.',
            'nombre_usuario.required' => 'The username field is required.',
            'nombre_usuario.unique' => 'This username is already taken.',
            'nombre_usuario.regex' => 'The username may only contain letters, numbers, dots, underscores, and dashes.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'celular.regex' => 'The phone number format is invalid.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
        ];
    }
}