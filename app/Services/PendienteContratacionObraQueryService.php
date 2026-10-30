<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EstadoContratacionObra;
use Illuminate\Database\Eloquent\Builder;

/**
 * Servicio de consulta de obras pendientes de contratación.
 *
 * Aplica filtros de búsqueda y el criterio de estado de contratación
 * (enum EstadoContratacionObra) sobre consultas Eloquent. Se utiliza desde
 * el recurso Filament PendienteContratacionObraResource (panel Obras).
 */
final class PendienteContratacionObraQueryService
{
    /**
     * Aplica los filtros de búsqueda textual (LIKE) sobre la consulta.
     *
     * @param array{expediente_id?: string|null, plan_obra?: string|null, num_obra?: string|null, subref?: string|null, ao_ejecucion?: string|null} $filters
     */
    public function applySearchFilters(Builder $query, array $filters): Builder
    {
        foreach ([
            'expediente_id' => 'expediente_id',
            'plan_obra' => 'PlanObra',
            'num_obra' => 'NumObra',
            'subref' => 'SubRef',
            'ao_ejecucion' => 'AoObra',
        ] as $filter => $column) {
            $value = trim((string) ($filters[$filter] ?? ''));

            if ($value !== '') {
                $query->where($column, 'like', "%{$value}%");
            }
        }

        return $query;
    }

    /**
     * Restringe la consulta a obras contratadas o sin contratar según el estado indicado.
     */
    public function applyContractingStatus(Builder $query, EstadoContratacionObra $status): Builder
    {
        $contracted = static function (Builder $contractedQuery): void {
            $contractedQuery
                ->whereNotNull('CodContratista')
                ->orWhereNotNull('FechaAdjudicacion')
                ->orWhereNotNull('ImporteAdjudicacion')
                ->orWhereNotNull('ImporteAdjudicacion_Pts');
        };

        if ($status === EstadoContratacionObra::Contratada) {
            $query->where($contracted);
        } else {
            $query->whereNot($contracted);
        }

        return $query;
    }
}
