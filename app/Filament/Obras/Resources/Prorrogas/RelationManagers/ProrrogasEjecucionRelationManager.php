<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Prorrogas\RelationManagers;

use App\Enums\ProrrogaTipo;
use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\ProrrogasRelationManager;

/**
 * Prórrogas del plazo de ejecución de la obra (menú «Ejecución → Prórrogas»).
 * Solo lista y da de alta prórrogas de tipo ejecución.
 */
class ProrrogasEjecucionRelationManager extends ProrrogasRelationManager
{
    protected static ?string $title = 'Prórrogas de ejecución';

    /**
     * @return list<ProrrogaTipo>
     */
    protected function tiposPermitidos(): ?array
    {
        return [ProrrogaTipo::EJECUCION];
    }

    protected function tipoPorDefecto(): ProrrogaTipo
    {
        return ProrrogaTipo::EJECUCION;
    }

    /**
     * @return list<string>
     */
    protected function tiposConAlcance(): array
    {
        return [];
    }
}
