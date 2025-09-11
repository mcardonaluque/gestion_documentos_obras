<?php

namespace App\Filament\Obras\Resources\AvisoResource\Pages;

use App\Filament\Obras\Resources\AvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvisos extends ListRecords
{
    protected static string $resource = AvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
