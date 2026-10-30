<?php

namespace App\Filament\Obras\Resources\Alertas\Pages;

use App\Filament\Obras\Resources\Alertas\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de alertas (sin lógica propia). */
class CreateAlerta extends CreateRecord
{
    protected static string $resource = AlertaResource::class;
}
