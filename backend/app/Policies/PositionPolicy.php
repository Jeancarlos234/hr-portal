<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;

class PositionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ver-cargos');
    }

    public function view(User $user, Position $position): bool
    {
        return $user->hasPermission('ver-cargos')
            && $this->sameCompany($user, $position);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('gestionar-cargos');
    }

    public function update(User $user, Position $position): bool
    {
        return $user->hasPermission('gestionar-cargos')
            && $this->sameCompany($user, $position);
    }

    public function delete(User $user, Position $position): bool
    {
        return $user->hasPermission('gestionar-cargos')
            && $this->sameCompany($user, $position);
    }

    private function sameCompany(User $user, Position $position): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->company_id === $position->company_id;
    }
}