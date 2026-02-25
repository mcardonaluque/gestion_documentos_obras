<?php

namespace App\Filament\Resources\FaseDocumentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FaseDocumentos\FaseDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaseDocumentos extends ListRecords
{
    protected static string $resource = FaseDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
