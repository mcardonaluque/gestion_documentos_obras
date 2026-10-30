<?php

namespace App\Filament\Resources\ExpedienteFechaHitos\Pages;

use App\Filament\Resources\ExpedienteFechaHitos\ExpedienteFechaHitoResource;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de ExpedienteFechaHitoResource (panel de administración). */
class ListExpedienteFechaHitos extends ListRecords
{
    protected static string $resource = ExpedienteFechaHitoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
