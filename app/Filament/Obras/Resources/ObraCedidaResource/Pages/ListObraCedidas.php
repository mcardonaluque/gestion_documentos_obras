<?php

namespace App\Filament\Obras\Resources\ObraCedidaResource\Pages;

use App\Filament\Obras\Resources\ObraCedidaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObraCedidas extends ListRecords
{
    protected static string $resource = ObraCedidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
