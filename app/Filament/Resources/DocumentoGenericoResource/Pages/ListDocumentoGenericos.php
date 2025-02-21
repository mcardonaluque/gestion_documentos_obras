<?php

namespace App\Filament\Resources\DocumentoGenericoResource\Pages;

use App\Filament\Resources\DocumentoGenericoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoGenericos extends ListRecords
{
    protected static string $resource = DocumentoGenericoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
