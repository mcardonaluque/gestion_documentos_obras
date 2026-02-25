<?php

namespace App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoexpedientes extends ListRecords
{
    protected static string $resource = DocumentoexpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
