<?php

namespace App\Filament\Resources\Documentoexpedientes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoexpediente extends EditRecord
{
    protected static string $resource = DocumentoexpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
