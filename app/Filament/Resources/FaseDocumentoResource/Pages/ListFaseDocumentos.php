<?php

namespace App\Filament\Resources\FaseDocumentoResource\Pages;

use App\Filament\Resources\FaseDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaseDocumentos extends ListRecords
{
    protected static string $resource = FaseDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
