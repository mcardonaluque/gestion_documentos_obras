<?php

namespace App\Filament\Resources\PorcentajesProyectos\Pages;

use App\Filament\Resources\PorcentajesProyectos\PorcentajesProyectosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de PorcentajesProyectosResource (panel de administración). */
class ListPorcentajesProyectos extends ListRecords
{
    protected static string $resource = PorcentajesProyectosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
