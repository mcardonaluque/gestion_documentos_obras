<?php

namespace App\Filament\Resources\TablaDeEstadosResource\Pages;

use App\Filament\Resources\TablaDeEstadosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeEstados extends ListRecords
{
    protected static string $resource = TablaDeEstadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
