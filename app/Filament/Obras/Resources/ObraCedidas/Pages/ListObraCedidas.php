<?php

namespace App\Filament\Obras\Resources\ObraCedidas\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\ObraCedidas\ObraCedidaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObraCedidas extends ListRecords
{
    protected static string $resource = ObraCedidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
