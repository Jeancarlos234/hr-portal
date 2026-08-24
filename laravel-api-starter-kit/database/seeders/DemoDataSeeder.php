<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed demo data.
     */
    public function run(): void
    {
        // Create demo user
        $demoUser = User::create([
            'nombre' => 'Demo',
            'apellido' => 'User',
            'nombre_usuario' => 'demouser',
            'email' => 'user@example.com',
            'celular' => '0999999999',
            'password' => Hash::make('Password123!'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $demoUser->assignRole('user');

        // Create additional demo users
        User::factory()->count(20)->create()->each(function ($user) {
            $user->assignRole('user');
        });

        $this->command->info('Demo data created successfully.');
    }
}