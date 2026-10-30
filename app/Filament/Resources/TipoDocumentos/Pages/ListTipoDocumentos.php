<?php

namespace App\Filament\Resources\TipoDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TipoDocumentos\TipoDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TipoDocumentoResource (panel de administración). */
class ListTipoDocumentos extends ListRecords
{
    protected static string $resource = TipoDocumentoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
