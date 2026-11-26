<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasAprobacion\RelationManagers;

use App\Enums\ProrrogaTipo;
use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\ProrrogasRelationManager;
use App\Models\DatosDeInicioDeObras;
use App\Models\DatosEjecucionObras;

/**
 * Prórrogas de la fase de aprobación de la obra (normativa, proyecto y
 * documentación). Excluye las de ejecución y justificación, que se gestionan
 * desde {@see \App\Filament\Obras\Resources\Prorrogas\ProrrogaResource}.
 *
 * Cuelga de DatosDeInicioDeObras, por lo que permite registrar prórrogas de
 * obras que todavía no tienen ficha de ejecución.
 */
class ProrrogasAprobacionRelationManager extends ProrrogasRelationManager
{
    protected static ?string $title = 'Prórrogas de aprobación';

    /**
     * @return list<ProrrogaTipo>
     */
    protected function tiposPermitidos(): ?array
    {
        return ProrrogaTipo::aprobacion();
    }

    protected function tipoPorDefecto(): ProrrogaTipo
    {
        return ProrrogaTipo::NORMATIVA;
    }

    /**
     * @return list<string>
     */
    protected function tiposConAlcance(): array
    {
        return ProrrogaTipo::valoresAprobacion();
    }

    protected function resolveObraPropietaria(): DatosEjecucionObras|DatosDeInicioDeObras|null
    {
        $owner = $this->getOwnerRecord();

        return $owner instanceof DatosDeInicioDeObras ? $owner : null;
    }
}
