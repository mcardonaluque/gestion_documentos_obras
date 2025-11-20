<?php

namespace App\Filament\Resources\TablaDeMunicipioResource\Pages;

use App\Filament\Resources\TablaDeMunicipioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeMunicipios extends ListRecords
{
    protected static string $resource = TablaDeMunicipioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
