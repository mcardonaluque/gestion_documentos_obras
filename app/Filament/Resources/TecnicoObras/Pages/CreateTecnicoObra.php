<?php

namespace App\Filament\Resources\TecnicoObras\Pages;

use App\Filament\Resources\TecnicoObras\TecnicoObraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de TecnicoObraResource (panel de administración). */
class CreateTecnicoObra extends CreateRecord
{
    protected static string $resource = TecnicoObraResource::class;
}
