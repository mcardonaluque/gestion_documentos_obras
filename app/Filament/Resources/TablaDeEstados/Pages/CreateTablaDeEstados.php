<?php

namespace App\Filament\Resources\TablaDeEstados\Pages;

use App\Filament\Resources\TablaDeEstados\TablaDeEstadosResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de TablaDeEstadosResource (panel de administración). */
class CreateTablaDeEstados extends CreateRecord
{
    protected static string $resource = TablaDeEstadosResource::class;
}
