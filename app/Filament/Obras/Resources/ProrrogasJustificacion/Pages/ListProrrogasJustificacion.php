<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasJustificacion\Pages;

use App\Filament\Obras\Resources\ProrrogasJustificacion\ProrrogaJustificacionResource;
use Filament\Resources\Pages\ListRecords;

/** Listado de obras para gestionar sus prórrogas de justificación. */
class ListProrrogasJustificacion extends ListRecords
{
    protected static string $resource = ProrrogaJustificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
