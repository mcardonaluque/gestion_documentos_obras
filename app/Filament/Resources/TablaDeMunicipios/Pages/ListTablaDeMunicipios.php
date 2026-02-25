<?php

namespace App\Filament\Resources\TablaDeMunicipios\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeMunicipios\TablaDeMunicipioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeMunicipios extends ListRecords
{
    protected static string $resource = TablaDeMunicipioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
