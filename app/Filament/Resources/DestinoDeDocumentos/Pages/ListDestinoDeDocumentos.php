<?php

namespace App\Filament\Resources\DestinoDeDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\DestinoDeDocumentos\DestinoDeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de DestinoDeDocumentosResource (panel de administración). */
class ListDestinoDeDocumentos extends ListRecords
{
    protected static string $resource = DestinoDeDocumentosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
