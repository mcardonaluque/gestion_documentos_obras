<?php

namespace App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages;

use App\Filament\Obras\Resources\ObrasPendientesProyecto\ObrasPendientesProyectoResource;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar de obras pendientes de proyecto (sin lógica propia). */
class ListObrasPendientesProyectos extends ListRecords
{
    protected static string $resource = ObrasPendientesProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
