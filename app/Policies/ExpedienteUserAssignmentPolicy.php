<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ExpedienteUserAssignment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy que protege el modelo `ExpedienteUserAssignment`
 * (asignación de expedientes a usuarios).
 *
 * Concede acceso total a `super_admin` mediante `before()` y, para el
 * resto de habilidades, exige el rol Abogado/abogado. La resuelve
 * automáticamente Laravel al autorizar las acciones del recurso de
 * asignaciones en los paneles de Filament.
 */
class ExpedienteUserAssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Concede todas las habilidades a los super administradores;
     * devuelve null para continuar con las comprobaciones normales.
     */
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

    /**
     * Regla común: solo los usuarios con rol Abogado pueden
     * gestionar asignaciones de expedientes.
     */
    private function canManageAssignments(User $user): bool
    {
        return $user->hasAnyRole(['Abogado', 'abogado']);
    }
}
