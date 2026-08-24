<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the application's admin user.
     */
    public function run(): void
    {
        $admin = User::create([
            'nombre' => env('ADMIN_NAME', 'Administrator'),
            'apellido' => env('ADMIN_LASTNAME', 'System'),
            'nombre_usuario' => env('ADMIN_USERNAME', 'admin'),
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'celular' => env('ADMIN_PHONE', null),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!')),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $admin->assignRole('admin');

        $this->command->info('Admin user created successfully.');
        $this->command->warn('Email: ' . env('ADMIN_EMAIL', 'admin@example.com'));
        $this->command->warn('Password: ' . env('ADMIN_PASSWORD', 'ChangeMe123!'));
        $this->command->warn('IMPORTANT: Change this password immediately after installation!');
    }
}