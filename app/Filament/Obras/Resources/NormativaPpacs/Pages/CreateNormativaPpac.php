<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\NormativaPpacs\Pages;

use App\Filament\Obras\Resources\NormativaPpacs\NormativaPpacResource;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de normativas PPAC (sin lógica propia). */
class CreateNormativaPpac extends CreateRecord
{
    protected static string $resource = NormativaPpacResource::class;
}
