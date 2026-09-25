<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Intenta autenticar al usuario y devuelve un token Sanctum.
     *
     * @throws ValidationException
     */
    public function login(string $email, string $password, string $deviceName = 'web'): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta está inactiva. Contacta al administrador.'],
            ]);
        }

        // Revocar tokens previos del mismo dispositivo (opcional)
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken($deviceName)->plainTextToken;

        $user->load('company', 'roles.permissions');

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Registra un nuevo usuario.
     */
    public function register(array $data, string $deviceName = 'web'): array
    {
        $user = User::create([
            'company_id' => $data['company_id'] ?? null,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'phone'      => $data['phone'] ?? null,
            'status'     => 'active',
        ]);

        // Rol por defecto
        $user->assignRole('empleado');

        $token = $user->createToken($deviceName)->plainTextToken;

        $user->load('company', 'roles.permissions');

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Revoca el token actual del usuario.
     */
    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }

    /**
     * Revoca TODOS los tokens del usuario.
     */
    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }
}