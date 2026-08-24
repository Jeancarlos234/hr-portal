<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder{
    /**
     * Seed the application's roles and permissions.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full system administrator with all permissions.',
            'is_active' => true,
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Standard user with limited permissions.',
            'is_active' => true,
        ]);

        // Define permissions
        $permissions = [
            // User permissions
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'users'],
            ['name' => 'Update Users', 'slug' => 'users.update', 'module' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'users'],

            // Role permissions
            ['name' => 'View Roles', 'slug' => 'roles.view', 'module' => 'roles'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'module' => 'roles'],
            ['name' => 'Update Roles', 'slug' => 'roles.update', 'module' => 'roles'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'module' => 'roles'],

            // Permission permissions
            ['name' => 'View Permissions', 'slug' => 'permissions.view', 'module' => 'permissions'],
            ['name' => 'Create Permissions', 'slug' => 'permissions.create', 'module' => 'permissions'],
            ['name' => 'Update Permissions', 'slug' => 'permissions.update', 'module' => 'permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'permissions.delete', 'module' => 'permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'module' => $permission['module'],
                'description' => $permission['name'] . ' permission.',
                'is_active' => true,
            ]);
        }

        // Assign all permissions to admin role
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Assign limited permissions to user role
        $userPermissions = Permission::where('slug', 'like', '%.view')->get();
        $userRole->permissions()->sync($userPermissions->pluck('id'));
    }
}