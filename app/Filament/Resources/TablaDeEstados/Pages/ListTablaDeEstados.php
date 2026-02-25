<?php

namespace App\Filament\Resources\TablaDeEstados\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeEstados\TablaDeEstadosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeEstados extends ListRecords
{
    protected static string $resource = TablaDeEstadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
