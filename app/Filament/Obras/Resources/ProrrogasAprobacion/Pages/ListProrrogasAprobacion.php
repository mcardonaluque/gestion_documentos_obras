<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasAprobacion\Pages;

use App\Filament\Obras\Resources\ProrrogasAprobacion\ProrrogaAprobacionResource;
use Filament\Resources\Pages\ListRecords;

/** Listado de obras de inicio para gestionar sus prórrogas de aprobación. */
class ListProrrogasAprobacion extends ListRecords
{
    protected static string $resource = ProrrogaAprobacionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
