<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ver-empleados');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->hasPermission('ver-empleados')
            && $this->sameCompany($user, $employee);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('crear-empleados');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->hasPermission('editar-empleados')
            && $this->sameCompany($user, $employee);
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasPermission('eliminar-empleados')
            && $this->sameCompany($user, $employee);
    }

    private function sameCompany(User $user, Employee $employee): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->company_id === $employee->company_id;
    }
}