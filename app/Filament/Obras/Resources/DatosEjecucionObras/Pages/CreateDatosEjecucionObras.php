<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\Pages;

use App\Filament\Obras\Resources\DatosEjecucionObras\DatosEjecucionObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de datos de ejecución de obras (sin lógica propia). */
class CreateDatosEjecucionObras extends CreateRecord
{
    protected static string $resource = DatosEjecucionObrasResource::class;
}
