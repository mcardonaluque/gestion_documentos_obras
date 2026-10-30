<?php

namespace App\Filament\Resources\DocumentoGenericos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\DocumentoGenericos\DocumentoGenericoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de DocumentoGenericoResource (panel de administración). */
class ListDocumentoGenericos extends ListRecords
{
    protected static string $resource = DocumentoGenericoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
