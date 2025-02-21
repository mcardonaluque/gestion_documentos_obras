<?php

namespace App\Filament\Resources\DocumentoExpedienteResource\Pages;

use App\Filament\Resources\DocumentoExpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoExpedientes extends ListRecords
{
    protected static string $resource = DocumentoExpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
