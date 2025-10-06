<?php

namespace App\Filament\Obras\Resources\DocumentoexpedienteResource\Pages;

use App\Filament\Obras\Resources\DocumentoexpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoexpediente extends EditRecord
{
    protected static string $resource = DocumentoexpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
