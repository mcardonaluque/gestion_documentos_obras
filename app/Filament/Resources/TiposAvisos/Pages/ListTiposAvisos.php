<?php

namespace App\Filament\Resources\TiposAvisos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TiposAvisos\TiposAvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TiposAvisoResource (panel de administración). */
class ListTiposAvisos extends ListRecords
{
    protected static string $resource = TiposAvisoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
