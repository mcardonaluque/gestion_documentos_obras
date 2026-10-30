<?php

namespace App\Filament\Resources\PorcentajesDeFinanciaciones\Pages;

use App\Filament\Resources\PorcentajesDeFinanciaciones\PorcentajesDeFinanciacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de PorcentajesDeFinanciacionResource (panel de administración). */
class ListPorcentajesDeFinanciaciones extends ListRecords
{
    protected static string $resource = PorcentajesDeFinanciacionResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
