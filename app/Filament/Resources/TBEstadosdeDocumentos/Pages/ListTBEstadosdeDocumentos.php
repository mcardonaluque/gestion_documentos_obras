<?php

namespace App\Filament\Resources\TBEstadosdeDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TBEstadosdeDocumentos\TBEstadosdeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TBEstadosdeDocumentosResource (panel de administración). */
class ListTBEstadosdeDocumentos extends ListRecords
{
    protected static string $resource = TBEstadosdeDocumentosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
