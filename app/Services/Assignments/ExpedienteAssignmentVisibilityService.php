<?php

declare(strict_types=1);

namespace App\Services\Assignments;

use App\Models\ExpedienteUserAssignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio de visibilidad de expedientes por asignación de usuario.
 *
 * Encapsula la lógica de filtrado para que los Resources, widgets y listados
 * puedan reutilizar una única política de acceso basada en expediente_id.
 */
class ExpedienteAssignmentVisibilityService
{
    /**
     * Devuelve la lista de expedientes asignados al usuario indicado.
     *
     * @return list<string>
     */
    public function assignedExpedienteIdsForUser(User $user): array
    {
        /** @var list<string> $expedienteIds */
        $expedienteIds = ExpedienteUserAssignment::query()
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('expediente_id')
            ->map(static fn (mixed $value): string => (string) $value)
            ->values()
            ->all();

        return $expedienteIds;
    }

    /**
     * Restringe una consulta al conjunto de expedientes visibles para el usuario autenticado.
     */
    public function scopeToCurrentUserAssigned(Builder $query, string $column = 'expediente_id'): Builder
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return $query;
        }

        if ($user->hasGlobalAyuntamientosAccess()) {
            return $query;
        }

        $assignedExpedientes = $this->assignedExpedienteIdsForUser($user);

        if ($assignedExpedientes === []) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn($column, $assignedExpedientes);
    }
}
