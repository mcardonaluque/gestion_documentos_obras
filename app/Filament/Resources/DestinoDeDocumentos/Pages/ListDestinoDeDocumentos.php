<?php

namespace App\Filament\Resources\DestinoDeDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\DestinoDeDocumentos\DestinoDeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDestinoDeDocumentos extends ListRecords
{
    protected static string $resource = DestinoDeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
