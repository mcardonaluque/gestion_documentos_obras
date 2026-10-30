<?php

namespace App\Filament\Obras\Resources\ObrasCedidas\Pages;

use App\Filament\Obras\Resources\ObrasCedidas\ObrasCedidasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación del recurso esqueleto de obras cedidas (sin lógica propia). */
class CreateObrasCedidas extends CreateRecord
{
    protected static string $resource = ObrasCedidasResource::class;
}
