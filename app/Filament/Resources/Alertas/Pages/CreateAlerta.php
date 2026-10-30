<?php

namespace App\Filament\Resources\Alertas\Pages;

use App\Filament\Resources\Alertas\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de AlertaResource (panel de administración). */
class CreateAlerta extends CreateRecord
{
    protected static string $resource = AlertaResource::class;
}
