<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObrasResource\Pages;

use App\Filament\Obras\Resources\DatosEjecucionObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatosEjecucionObras extends ListRecords
{
    protected static string $resource = DatosEjecucionObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
