<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use Filament\Resources\Pages\ViewRecord;

/** Página de consulta (solo lectura) de un expediente del panel de ayuntamientos. */
class ViewExpediente extends ViewRecord
{
    protected static string $resource = ExpedienteResource::class;
}
