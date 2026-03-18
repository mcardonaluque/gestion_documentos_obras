<?php

namespace App\Filament\Obras\Resources\ActasDeRecepcion\Pages;

use App\Filament\Obras\Resources\ActasDeRecepcion\ActaderecepcionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActaderecepcions extends ListRecords
{
    protected static string $resource = ActaderecepcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
