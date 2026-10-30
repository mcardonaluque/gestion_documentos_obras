<?php

namespace App\Filament\Resources\TablaDeCarreteras\Pages;

use App\Filament\Resources\TablaDeCarreteras\TablaDeCarreteraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de TablaDeCarreteraResource (panel de administración). */
class CreateTablaDeCarretera extends CreateRecord
{
    protected static string $resource = TablaDeCarreteraResource::class;
}
