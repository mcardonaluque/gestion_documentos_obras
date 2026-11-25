<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo\Pages;

use App\Filament\Obras\Resources\ActasDeReplanteo\ActasdereplanteoResource;
use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de actas de replanteo (sin lógica propia). */
class CreateActadereplanteo extends CreateRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = ActasdereplanteoResource::class;
}
