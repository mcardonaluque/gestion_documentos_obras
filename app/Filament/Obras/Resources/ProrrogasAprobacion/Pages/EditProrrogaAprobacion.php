<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasAprobacion\Pages;

use App\Filament\Obras\Resources\ProrrogasAprobacion\ProrrogaAprobacionResource;
use Filament\Resources\Pages\EditRecord;

/** Ficha de la obra que aloja el relation manager de prórrogas de aprobación. */
class EditProrrogaAprobacion extends EditRecord
{
    protected static string $resource = ProrrogaAprobacionResource::class;

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
