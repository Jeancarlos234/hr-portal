<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    /**
     * ¿Puede ver la lista?
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ver-departamentos');
    }

    /**
     * ¿Puede ver un departamento específico?
     */
    public function view(User $user, Department $department): bool
    {
        return $user->hasPermission('ver-departamentos')
            && $this->sameCompany($user, $department);
    }

    /**
     * ¿Puede crear?
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('gestionar-departamentos');
    }

    /**
     * ¿Puede editar?
     */
    public function update(User $user, Department $department): bool
    {
        return $user->hasPermission('gestionar-departamentos')
            && $this->sameCompany($user, $department);
    }

    /**
     * ¿Puede eliminar?
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->hasPermission('gestionar-departamentos')
            && $this->sameCompany($user, $department);
    }

    /**
     * Verifica que el usuario y el recurso sean de la misma empresa.
     * Super admin siempre pasa.
     */
    private function sameCompany(User $user, Department $department): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->company_id === $department->company_id;
    }
}