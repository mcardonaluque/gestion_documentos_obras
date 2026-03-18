<?php

namespace App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages;

use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoexpediente extends EditRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = DocumentoexpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
