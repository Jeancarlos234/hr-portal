<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $modules = ['users', 'roles', 'permissions', 'reports', 'settings', 'dashboard'];
        $actions = ['view', 'create', 'update', 'delete', 'export', 'import'];
        
        $module = fake()->randomElement($modules);
        $action = fake()->randomElement($actions);
        $name = ucfirst($action) . ' ' . ucfirst($module);

        return [
            'name' => $name,
            'slug' => $module . '.' . $action,
            'module' => $module,
            'description' => 'Permission to ' . $action . ' ' . $module . '.',
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the permission is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}