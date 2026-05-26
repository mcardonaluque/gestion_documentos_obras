<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Concerns;

use App\Services\Assignments\ExpedienteAssignmentVisibilityService;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait que añade visibilidad condicionada por expedientes asignados.
 *
 * Se usa en Resources y widgets para incorporar un filtro estándar que limite
 * la consulta a los expedientes vinculados al usuario autenticado.
 */
trait HasAssignedExpedienteVisibility
{
    /**
     * Define la columna del recurso que representa el identificador de expediente.
     */
    protected static function assignedExpedienteColumn(): string
    {
        return 'expediente_id';
    }

    /**
     * Devuelve el filtro reusable de “solo expedientes asignados”.
     */
    protected static function assignedExpedientesFilter(): Filter
    {
        return Filter::make('solo_expedientes_asignados')
            ->label('Solo expedientes asignados')
            ->schema([
                Toggle::make('enabled')
                    ->label('Aplicar filtro')
                    ->default(false),
            ])
            ->query(function (Builder $query, array $data): Builder {
                if (! ((bool) ($data['enabled'] ?? false))) {
                    return $query;
                }

                /** @var ExpedienteAssignmentVisibilityService $service */
                $service = app(ExpedienteAssignmentVisibilityService::class);

                return $service->scopeToCurrentUserAssigned($query, static::assignedExpedienteColumn());
            })
            ->indicateUsing(static fn (array $data): ?string => ((bool) ($data['enabled'] ?? false)) ? 'Solo asignados' : null);
    }
}
