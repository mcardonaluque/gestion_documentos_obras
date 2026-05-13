<?php

namespace App\Filament\Resources\TipoContratistas\Pages;

use App\Filament\Resources\TipoContratistas\TipoContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoContratistas extends ListRecords
{
    protected static string $resource = TipoContratistaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
