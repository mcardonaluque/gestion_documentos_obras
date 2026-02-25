<?php

namespace App\Filament\Resources\TBEstadosdeDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TBEstadosdeDocumentos\TBEstadosdeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTBEstadosdeDocumentos extends ListRecords
{
    protected static string $resource = TBEstadosdeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
