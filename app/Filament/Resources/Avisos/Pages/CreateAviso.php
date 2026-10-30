<?php

namespace App\Filament\Resources\Avisos\Pages;

use App\Filament\Resources\Avisos\AvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de AvisoResource (panel de administración). */
class CreateAviso extends CreateRecord
{
    protected static string $resource = AvisoResource::class;
}
