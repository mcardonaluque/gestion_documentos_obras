<?php

namespace App\Filament\Resources\Avisos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Avisos\AvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvisos extends ListRecords
{
    protected static string $resource = AvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
