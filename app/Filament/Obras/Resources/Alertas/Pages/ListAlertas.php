<?php

namespace App\Filament\Obras\Resources\Alertas\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\Alertas\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar de alertas (sin lógica propia). */
class ListAlertas extends ListRecords
{
    protected static string $resource = AlertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
