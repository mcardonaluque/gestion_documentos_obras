<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de expedientes (no registrada actualmente en las rutas del recurso). */
class CreateExpediente extends CreateRecord
{
    protected static string $resource = ExpedienteResource::class;
}
