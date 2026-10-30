<?php

namespace App\Filament\Obras\Resources\ImportesDeObras\Pages;

use App\Filament\Obras\Resources\ImportesDeObras\ImportesDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de importes de obras (sin lógica propia). */
class CreateImportesDeobras extends CreateRecord
{
    protected static string $resource = ImportesDeObrasResource::class;
}
