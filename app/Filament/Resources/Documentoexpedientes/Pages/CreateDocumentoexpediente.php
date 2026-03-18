<?php

namespace App\Filament\Resources\Documentoexpedientes\Pages;

use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use App\Filament\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use App\Models\DocumentoExpediente;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentoexpediente extends CreateRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = DocumentoexpedienteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return DocumentoExpediente::applyExpedienteDefaults($data);
    }
}
