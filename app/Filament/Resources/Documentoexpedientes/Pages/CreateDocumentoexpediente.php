<?php

namespace App\Filament\Resources\Documentoexpedientes\Pages;

use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use App\Filament\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use App\Models\DocumentoExpediente;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página de creación de documentos de expediente (panel de administración).
 *
 * Usa el trait NotifiesAndValidatesDates para validar fechas y notificar,
 * y completa los datos por defecto del expediente antes de crear.
 */
class CreateDocumentoexpediente extends CreateRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = DocumentoexpedienteResource::class;

    /** Completa los datos con los valores por defecto del expediente antes de crear. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return DocumentoExpediente::applyExpedienteDefaults($data);
    }
}
