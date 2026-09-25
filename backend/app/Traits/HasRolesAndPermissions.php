<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRolesAndPermissions
{
    // ==========================================
    // RELACIONES
    // ==========================================

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
                    ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
                    ->withTimestamps();
    }

    // ==========================================
    // ROLES
    // ==========================================

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return $this->roles()
                    ->whereIn('slug', $roles)
                    ->exists();
    }

    public function assignRole(string|Role $role): void
    {
        $roleId = $role instanceof Role
            ? $role->id
            : Role::where('slug', $role)->firstOrFail()->id;

        $this->roles()->syncWithoutDetaching([$roleId]);
    }

    public function removeRole(string|Role $role): void
    {
        $roleId = $role instanceof Role
            ? $role->id
            : Role::where('slug', $role)->firstOrFail()->id;

        $this->roles()->detach($roleId);
    }

    // ==========================================
    // PERMISOS
    // ==========================================

    /**
     * Verifica si el usuario tiene un permiso.
     * Puede venir directo por rol o por asignación directa.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // 1. ¿Tiene el permiso directo (vía roles)?
        $hasViaRoles = $this->roles()
                            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                                $query->where('slug', $permissionSlug);
                            })
                            ->exists();

        if ($hasViaRoles) {
            return true;
        }

        // 2. ¿Tiene el permiso asignado directamente al usuario?
        return $this->permissions()
                    ->where('slug', $permissionSlug)
                    ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function givePermissionTo(string|Permission $permission): void
    {
        $permissionId = $permission instanceof Permission
            ? $permission->id
            : Permission::where('slug', $permission)->firstOrFail()->id;

        $this->permissions()->syncWithoutDetaching([$permissionId]);
    }

    public function revokePermissionTo(string|Permission $permission): void
    {
        $permissionId = $permission instanceof Permission
            ? $permission->id
            : Permission::where('slug', $permission)->firstOrFail()->id;

        $this->permissions()->detach($permissionId);
    }

    /**
     * Devuelve todos los permisos efectivos: por roles + directos.
     */
    public function getAllPermissions(): array
    {
        $viaRoles = $this->roles()
                        ->with('permissions')
                        ->get()
                        ->pluck('permissions')
                        ->flatten()
                        ->pluck('slug')
                        ->unique()
                        ->values()
                        ->toArray();

        $directos = $this->permissions()->pluck('slug')->toArray();

        return array_values(array_unique(array_merge($viaRoles, $directos)));
    }
}