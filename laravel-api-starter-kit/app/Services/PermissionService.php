<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionService
{
    /**
     * Get paginated permissions with filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Permission::query()
            ->withCount('roles');

        // Apply search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply filters
        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (!empty($filters['is_active'])) {
            $query->where('is_active', $filters['is_active'] === 'true');
        }

        // Apply sorting
        $sortField = $filters['sort'] ?? 'module';
        $sortDirection = 'asc';

        if (str_starts_with($sortField, '-')) {
            $sortField = substr($sortField, 1);
            $sortDirection = 'desc';
        }

        $allowedSortFields = ['id', 'name', 'slug', 'module', 'is_active', 'created_at', 'updated_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
            if ($sortField !== 'module' && $sortField !== 'name') {
                $query->orderBy('module');
            }
        } else {
            $query->orderBy('module')->orderBy('name');
        }

        return $query->paginate($perPage);
    }

    /**
     * Get permission by ID.
     */
    public function getById(int $id): Permission
    {
        return Permission::with('roles:id,name,slug')->withCount('roles')->findOrFail($id);
    }

    /**
     * Create a new permission.
     */
    public function create(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'module' => $data['module'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update a permission.
     */
    public function update(Permission $permission, array $data): Permission
    {
        $updateData = [];

        foreach (['name', 'slug', 'module', 'description', 'is_active'] as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $permission->update($updateData);
        }

        return $permission->load('roles');
    }

    /**
     * Delete a permission (soft delete).
     */
    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}