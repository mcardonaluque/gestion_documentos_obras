<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de expedientes del panel de ayuntamientos (sin acción de creación). */
class ListExpedientes extends ListRecords
{
    protected static string $resource = ExpedienteResource::class;

    /** Sin acciones de cabecera: la creación de expedientes no está permitida en este panel. */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
