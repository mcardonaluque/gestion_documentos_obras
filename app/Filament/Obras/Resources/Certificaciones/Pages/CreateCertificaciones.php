<?php

namespace App\Filament\Obras\Resources\Certificaciones\Pages;

use App\Filament\Obras\Resources\Certificaciones\CertificacionesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de certificaciones (sin lógica propia). */
class CreateCertificaciones extends CreateRecord
{
    protected static string $resource = CertificacionesResource::class;
}
