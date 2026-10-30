<?php

namespace App\Filament\Resources\Expedientes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Expedientes\ExpedientesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de ExpedientesResource (panel de administración). */
class ListExpedientes extends ListRecords
{
    protected static string $resource = ExpedientesResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
