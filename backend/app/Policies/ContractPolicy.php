<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ver-contratos');
    }

    public function view(User $user, Contract $contract): bool
    {
        return $user->hasPermission('ver-contratos')
            && $this->sameCompany($user, $contract);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('gestionar-contratos');
    }

    public function update(User $user, Contract $contract): bool
    {
        return $user->hasPermission('gestionar-contratos')
            && $this->sameCompany($user, $contract);
    }

    public function delete(User $user, Contract $contract): bool
    {
        return $user->hasPermission('gestionar-contratos')
            && $this->sameCompany($user, $contract);
    }

    private function sameCompany(User $user, Contract $contract): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->company_id === $contract->company_id;
    }
}