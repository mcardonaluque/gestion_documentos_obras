<?php

namespace App\Filament\Resources\TablaDeDepartamentoResource\Pages;

use App\Filament\Resources\TablaDeDepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeDepartamentos extends ListRecords
{
    protected static string $resource = TablaDeDepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
