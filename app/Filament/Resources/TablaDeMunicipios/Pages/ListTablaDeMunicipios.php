<?php

namespace App\Filament\Resources\TablaDeMunicipios\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeMunicipios\TablaDeMunicipioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TablaDeMunicipioResource (panel de administración). */
class ListTablaDeMunicipios extends ListRecords
{
    protected static string $resource = TablaDeMunicipioResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
