<?php

namespace App\Filament\Resources\Expedientes\Pages;

use App\Filament\Resources\Expedientes\ExpedientesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de ExpedientesResource (panel de administración). */
class CreateExpedientes extends CreateRecord
{
    protected static string $resource = ExpedientesResource::class;
}
