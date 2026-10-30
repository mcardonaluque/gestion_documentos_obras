<?php

namespace App\Filament\Resources\FaseDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FaseDocumentos\FaseDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de FaseDocumentoResource (panel de administración). */
class ListFaseDocumentos extends ListRecords
{
    protected static string $resource = FaseDocumentoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
