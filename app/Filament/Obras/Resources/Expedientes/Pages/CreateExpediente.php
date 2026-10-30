<?php

namespace App\Filament\Obras\Resources\Expedientes\Pages;

use App\Filament\Obras\Resources\Expedientes\ExpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de expedientes (sin lógica propia). */
class CreateExpediente extends CreateRecord
{
    protected static string $resource = ExpedienteResource::class;
}
