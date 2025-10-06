<?php

namespace App\Filament\Obras\Resources\DocumentoexpedienteResource\Pages;

use App\Filament\Obras\Resources\DocumentoexpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoexpedientes extends ListRecords
{
    protected static string $resource = DocumentoexpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
