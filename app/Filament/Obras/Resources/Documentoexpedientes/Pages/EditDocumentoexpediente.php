<?php

namespace App\Filament\Obras\Resources\Documentoexpedientes\Pages;

use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions;
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
