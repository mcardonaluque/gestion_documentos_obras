<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasJustificacion\RelationManagers;

use App\Enums\ProrrogaTipo;
use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\ProrrogasRelationManager;

/**
 * Prórrogas del plazo de justificación de la obra (menú «Justificación →
 * Prórrogas de justificación»). Solo lista y da de alta prórrogas de tipo
 * justificación, que actualizan el plazo activo de la fase «justificacion».
 */
class ProrrogasJustificacionRelationManager extends ProrrogasRelationManager
{
    protected static ?string $title = 'Prórrogas de justificación';

    /**
     * @return list<ProrrogaTipo>
     */
    protected function tiposPermitidos(): ?array
    {
        return [ProrrogaTipo::JUSTIFICACION];
    }

    protected function tipoPorDefecto(): ProrrogaTipo
    {
        return ProrrogaTipo::JUSTIFICACION;
    }

    /**
     * @return list<string>
     */
    protected function tiposConAlcance(): array
    {
        return [];
    }
}
