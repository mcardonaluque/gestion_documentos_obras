<?php

namespace App\Filament\Resources\PorcentajesProyectos\Pages;

use App\Filament\Resources\PorcentajesProyectos\PorcentajesProyectosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPorcentajesProyectos extends ListRecords
{
    protected static string $resource = PorcentajesProyectosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
