<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService
{
    /**
     * Get paginated roles with filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Role::query()
            ->with('permissions:id,name,slug,module')
            ->withCount('users');

        // Apply search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply filters
        if (!empty($filters['is_active'])) {
            $query->where('is_active', $filters['is_active'] === 'true');
        }

        // Apply sorting
        $sortField = $filters['sort'] ?? 'name';
        $sortDirection = 'asc';

        if (str_starts_with($sortField, '-')) {
            $sortField = substr($sortField, 1);
            $sortDirection = 'desc';
        }

        $allowedSortFields = ['id', 'name', 'slug', 'is_active', 'created_at', 'updated_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('name');
        }

        return $query->paginate($perPage);
    }

    /**
     * Get role by ID.
     */
    public function getById(int $id): Role
    {
        return Role::with('permissions')->withCount('users')->findOrFail($id);
    }

    /**
     * Create a new role.
     */
    public function create(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

    /**
     * Update a role.
     */
    public function update(Role $role, array $data): Role
    {
        $updateData = [];

        foreach (['name', 'slug', 'description', 'is_active'] as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $role->update($updateData);
        }

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

    /**
     * Delete a role (soft delete).
     */
    public function delete(Role $role): void
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            throw new \Exception('Cannot delete role with assigned users.', 409);
        }

        $role->delete();
    }
}