<?php

namespace App\Filament\Resources\Alertas\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Alertas\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de AlertaResource (panel de administración). */
class ListAlertas extends ListRecords
{
    protected static string $resource = AlertaResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
