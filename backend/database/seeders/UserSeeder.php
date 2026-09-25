<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('tax_id', '1799999999001')->first();

        // ==========================================
        // SUPER ADMIN (sin empresa — acceso global)
        // ==========================================
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@rrhh.test'],
            [
                'company_id' => null,
                'name'       => 'Super Administrador',
                'password'   => Hash::make('password'),
                'phone'      => '+593 99 000 0001',
                'status'     => 'active',
            ]
        );
        $superAdmin->roles()->sync(
            Role::where('slug', 'super-admin')->pluck('id')->toArray()
        );

        // ==========================================
        // ADMINISTRADOR DE LA EMPRESA DEMO
        // ==========================================
        $admin = User::updateOrCreate(
            ['email' => 'admin@empresademo.com'],
            [
                'company_id' => $company->id,
                'name'       => 'Administrador Demo',
                'password'   => Hash::make('password'),
                'phone'      => '+593 99 000 0002',
                'status'     => 'active',
            ]
        );
        $admin->roles()->sync(
            Role::where('slug', 'administrador')->pluck('id')->toArray()
        );

        // ==========================================
        // RRHH
        // ==========================================
        $rrhh = User::updateOrCreate(
            ['email' => 'rrhh@empresademo.com'],
            [
                'company_id' => $company->id,
                'name'       => 'María RRHH',
                'password'   => Hash::make('password'),
                'phone'      => '+593 99 000 0003',
                'status'     => 'active',
            ]
        );
        $rrhh->roles()->sync(
            Role::where('slug', 'rrhh')->pluck('id')->toArray()
        );

        // ==========================================
        // SUPERVISOR
        // ==========================================
        $supervisor = User::updateOrCreate(
            ['email' => 'supervisor@empresademo.com'],
            [
                'company_id' => $company->id,
                'name'       => 'Carlos Supervisor',
                'password'   => Hash::make('password'),
                'phone'      => '+593 99 000 0004',
                'status'     => 'active',
            ]
        );
        $supervisor->roles()->sync(
            Role::where('slug', 'supervisor')->pluck('id')->toArray()
        );

        // ==========================================
        // EMPLEADO
        // ==========================================
        $empleado = User::updateOrCreate(
            ['email' => 'empleado@empresademo.com'],
            [
                'company_id' => $company->id,
                'name'       => 'Juan Empleado',
                'password'   => Hash::make('password'),
                'phone'      => '+593 99 000 0005',
                'status'     => 'active',
            ]
        );
        $empleado->roles()->sync(
            Role::where('slug', 'empleado')->pluck('id')->toArray()
        );

        $this->command->info('✅ Usuarios creados: 5');
        $this->command->warn('📧 Credenciales (password: "password"):');
        $this->command->line('   superadmin@rrhh.test     → Super Admin');
        $this->command->line('   admin@empresademo.com    → Administrador');
        $this->command->line('   rrhh@empresademo.com     → RRHH');
        $this->command->line('   supervisor@empresademo.com → Supervisor');
        $this->command->line('   empleado@empresademo.com → Empleado');
    }
}