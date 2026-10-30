<?php

namespace App\Filament\Obras\Resources\ActasDeRecepcion\Pages;

use App\Filament\Obras\Resources\ActasDeRecepcion\ActaderecepcionResource;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de actas de recepción (sin lógica propia). */
class CreateActaderecepcion extends CreateRecord
{
    protected static string $resource = ActaderecepcionResource::class;
}
