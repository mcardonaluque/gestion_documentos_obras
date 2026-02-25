<?php

namespace App\Filament\Obras\Resources\Documentoexpedientes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions;
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
