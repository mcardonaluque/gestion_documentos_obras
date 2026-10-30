<?php

namespace App\Filament\Resources\TipoContratistas\Pages;

use App\Filament\Resources\TipoContratistas\TipoContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TipoContratistaResource (panel de administración). */
class ListTipoContratistas extends ListRecords
{
    protected static string $resource = TipoContratistaResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
