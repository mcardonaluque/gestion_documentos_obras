<?php

namespace App\Filament\Resources\Documentoexpedientes\Pages;

use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Página de edición de documentos de expediente (panel de administración).
 *
 * Usa el trait NotifiesAndValidatesDates para validar fechas y notificar al guardar.
 */
class EditDocumentoexpediente extends EditRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = DocumentoexpedienteResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
