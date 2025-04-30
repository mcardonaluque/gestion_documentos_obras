<?php

namespace App\Filament\Resources\DestinoDeDocumentosResource\Pages;

use App\Filament\Resources\DestinoDeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDestinoDeDocumentos extends ListRecords
{
    protected static string $resource = DestinoDeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
