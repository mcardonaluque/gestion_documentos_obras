<?php

namespace App\Filament\Obras\Resources\ActasDeRecepcion\Pages;

use App\Filament\Obras\Resources\ActasDeRecepcion\ActaderecepcionResource;
use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de actas de recepción (sin lógica propia). */
class CreateActaderecepcion extends CreateRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = ActaderecepcionResource::class;
}
