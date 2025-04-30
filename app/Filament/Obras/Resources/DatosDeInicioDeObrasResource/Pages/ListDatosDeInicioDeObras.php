<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\Pages;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatosDeInicioDeObras extends ListRecords
{
    protected static string $resource = DatosDeInicioDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
