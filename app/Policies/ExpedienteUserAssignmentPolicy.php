<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ExpedienteUserAssignment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpedienteUserAssignmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canManageAssignments($user);
    }

    public function view(User $user, ExpedienteUserAssignment $expedienteUserAssignment): bool
    {
        return $this->canManageAssignments($user);
    }

    public function create(User $user): bool
    {
        return $this->canManageAssignments($user);
    }

    public function update(User $user, ExpedienteUserAssignment $expedienteUserAssignment): bool
    {
        return $this->canManageAssignments($user);
    }

    public function delete(User $user, ExpedienteUserAssignment $expedienteUserAssignment): bool
    {
        return $this->canManageAssignments($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canManageAssignments($user);
    }

    private function canManageAssignments(User $user): bool
    {
        return $user->hasAnyRole(['Abogado', 'abogado']);
    }
}
