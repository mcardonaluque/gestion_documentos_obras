<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages;

use App\Filament\Obras\Resources\DatosDeInicioDeObras\DatosDeInicioDeObrasResource;
use Filament\Resources\Pages\ViewRecord;

/** Vista estándar de los datos de inicio de obra (sin lógica propia). */
class ViewInicioDeObra extends ViewRecord
{
    protected static string $resource = DatosDeInicioDeObrasResource::class;
}
