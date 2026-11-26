<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasJustificacion\Pages;

use App\Filament\Obras\Resources\ProrrogasJustificacion\ProrrogaJustificacionResource;
use Filament\Resources\Pages\EditRecord;

/** Ficha de la obra que aloja el relation manager de prórrogas de justificación. */
class EditProrrogaJustificacion extends EditRecord
{
    protected static string $resource = ProrrogaJustificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    /** La ficha es solo informativa: no se muestran los botones de guardar. */
    protected function getFormActions(): array
    {
        return [];
    }
}
