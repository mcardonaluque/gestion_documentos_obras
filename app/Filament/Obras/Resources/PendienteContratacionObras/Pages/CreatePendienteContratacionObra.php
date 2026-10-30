<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\PendienteContratacionObras\Pages;

use App\Filament\Obras\Resources\PendienteContratacionObras\PendienteContratacionObraResource;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de contrataciones de obra (sin lógica propia). */
final class CreatePendienteContratacionObra extends CreateRecord
{
    protected static string $resource = PendienteContratacionObraResource::class;
}
