<?php

namespace App\Filament\Resources\TipoContratistas\Pages;

use App\Filament\Resources\TipoContratistas\TipoContratistaResource;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de TipoContratistaResource (panel de administración). */
class CreateTipoContratista extends CreateRecord
{
    protected static string $resource = TipoContratistaResource::class;
}
