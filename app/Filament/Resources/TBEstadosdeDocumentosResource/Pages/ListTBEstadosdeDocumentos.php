<?php

namespace App\Filament\Resources\TBEstadosdeDocumentosResource\Pages;

use App\Filament\Resources\TBEstadosdeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTBEstadosdeDocumentos extends ListRecords
{
    protected static string $resource = TBEstadosdeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
