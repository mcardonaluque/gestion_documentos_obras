<?php

namespace App\Filament\Obras\Resources\Avisos\Pages;

use App\Filament\Obras\Resources\Avisos\AvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de avisos (sin lógica propia). */
class CreateAviso extends CreateRecord
{
    protected static string $resource = AvisoResource::class;
}
