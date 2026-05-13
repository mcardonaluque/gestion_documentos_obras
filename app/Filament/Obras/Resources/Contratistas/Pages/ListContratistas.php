<?php

namespace App\Filament\Obras\Resources\Contratistas\Pages;

use App\Filament\Obras\Resources\Contratistas\ContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContratistas extends ListRecords
{
    protected static string $resource = ContratistaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
