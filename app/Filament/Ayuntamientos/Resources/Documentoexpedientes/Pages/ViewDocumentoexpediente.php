<?php

namespace App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

/**
 * Página de consulta de un documento de expediente (panel de ayuntamientos).
 *
 * Añade una acción «Cancelar» que devuelve al listado del recurso.
 */
class ViewDocumentoexpediente extends ViewRecord
{
    protected static string $resource = DocumentoexpedienteResource::class;

    /** Acción de cabecera «Cancelar» para volver al listado. */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelar')
                ->label('Cancelar')
                ->color('gray')
                ->url($this->getRedirectUrl()),
        ];
    }

    /** URL de retorno al listado de documentos del expediente. */
    protected function getRedirectUrl(): string
    {
        return DocumentoexpedienteResource::getUrl('');
    }
}
