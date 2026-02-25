<?php

namespace App\Filament\Resources\DocumentoGenericos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\DocumentoGenericos\DocumentoGenericoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoGenericos extends ListRecords
{
    protected static string $resource = DocumentoGenericoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
