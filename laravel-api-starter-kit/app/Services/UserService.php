<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get paginated users with filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query()
            ->with('roles:id,name,slug')
            ->withCount('roles');

        // Apply search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('nombre_usuario', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('celular', 'like', "%{$search}%");
            });
        }

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        if (!empty($filters['email_verified'])) {
            if ($filters['email_verified'] === 'true') {
                $query->whereNotNull('email_verified_at');
            } elseif ($filters['email_verified'] === 'false') {
                $query->whereNull('email_verified_at');
            }
        }

        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }

        if (!empty($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', function (Builder $q) use ($filters) {
                $q->where('slug', $filters['role']);
            });
        }

        // Apply sorting
        $sortField = $filters['sort'] ?? 'created_at';
        $sortDirection = 'asc';

        if (str_starts_with($sortField, '-')) {
            $sortField = substr($sortField, 1);
            $sortDirection = 'desc';
        }

        $allowedSortFields = [
            'id', 'nombre', 'apellido', 'nombre_usuario',
            'email', 'celular', 'is_active', 'created_at', 'updated_at',
        ];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Get user by ID.
     */
    public function getById(int $id): User
    {
        return User::with('roles.permissions')->findOrFail($id);
    }

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        $user = User::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'nombre_usuario' => $data['nombre_usuario'],
            'email' => $data['email'],
            'celular' => $data['celular'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user->load('roles.permissions');
    }

    /**
     * Update a user.
     */
    public function update(User $user, array $data): User
    {
        $updateData = [];

        foreach (['nombre', 'apellido', 'nombre_usuario', 'email', 'celular', 'is_active'] as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user->load('roles.permissions');
    }

    /**
     * Delete a user (soft delete).
     */
    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     * Restore a deleted user.
     */
    public function restore(int $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return $user->load('roles.permissions');
    }
}