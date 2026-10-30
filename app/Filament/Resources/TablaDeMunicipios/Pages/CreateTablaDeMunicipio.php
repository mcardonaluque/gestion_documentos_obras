<?php

namespace App\Filament\Resources\TablaDeMunicipios\Pages;

use App\Filament\Resources\TablaDeMunicipios\TablaDeMunicipioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de TablaDeMunicipioResource (panel de administración). */
class CreateTablaDeMunicipio extends CreateRecord
{
    protected static string $resource = TablaDeMunicipioResource::class;
}
