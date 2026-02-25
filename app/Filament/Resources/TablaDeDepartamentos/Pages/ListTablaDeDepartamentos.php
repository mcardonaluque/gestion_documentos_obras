<?php

namespace App\Filament\Resources\TablaDeDepartamentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeDepartamentos\TablaDeDepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeDepartamentos extends ListRecords
{
    protected static string $resource = TablaDeDepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
