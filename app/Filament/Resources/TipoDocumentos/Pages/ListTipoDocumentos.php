<?php

namespace App\Filament\Resources\TipoDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TipoDocumentos\TipoDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoDocumentos extends ListRecords
{
    protected static string $resource = TipoDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
