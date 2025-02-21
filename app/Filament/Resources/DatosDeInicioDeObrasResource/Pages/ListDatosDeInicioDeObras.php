<?php

namespace App\Filament\Resources\DatosDeInicioDeObrasResource\Pages;

use App\Filament\Resources\DatosDeInicioDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatosDeInicioDeObras extends ListRecords
{
    protected static string $resource = DatosDeInicioDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
