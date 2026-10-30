<?php

namespace App\Filament\Resources\TablaDeEstados\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeEstados\TablaDeEstadosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TablaDeEstadosResource (panel de administración). */
class ListTablaDeEstados extends ListRecords
{
    protected static string $resource = TablaDeEstadosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
