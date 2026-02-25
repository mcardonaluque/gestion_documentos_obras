<?php

namespace App\Filament\Resources\TiposAvisos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TiposAvisos\TiposAvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTiposAvisos extends ListRecords
{
    protected static string $resource = TiposAvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
