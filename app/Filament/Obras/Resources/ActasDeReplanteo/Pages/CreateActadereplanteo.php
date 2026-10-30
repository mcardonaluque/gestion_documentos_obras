<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo\Pages;

use App\Filament\Obras\Resources\ActasDeReplanteo\ActasdereplanteoResource;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de actas de replanteo (sin lógica propia). */
class CreateActadereplanteo extends CreateRecord
{
    protected static string $resource = ActasdereplanteoResource::class;
}
