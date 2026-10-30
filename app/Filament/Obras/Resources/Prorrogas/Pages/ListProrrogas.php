<?php

namespace App\Filament\Obras\Resources\Prorrogas\Pages;

use App\Filament\Obras\Resources\Prorrogas\ProrrogaResource;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar de expedientes para la gestión de prórrogas (sin lógica propia). */
class ListProrrogas extends ListRecords
{
    protected static string $resource = ProrrogaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
