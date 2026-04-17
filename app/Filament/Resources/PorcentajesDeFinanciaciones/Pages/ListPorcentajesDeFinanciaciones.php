<?php

namespace App\Filament\Resources\PorcentajesDeFinanciaciones\Pages;

use App\Filament\Resources\PorcentajesDeFinanciaciones\PorcentajesDeFinanciacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPorcentajesDeFinanciaciones extends ListRecords
{
    protected static string $resource = PorcentajesDeFinanciacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
