<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,   // 1. Permisos primero
            RoleSeeder::class,         // 2. Roles (usan permisos)
            CompanySeeder::class,      // 3. Empresa (la usan los usuarios)
            UserSeeder::class,         // 4. Usuarios (usan empresa y roles)
        ]);
    }
}