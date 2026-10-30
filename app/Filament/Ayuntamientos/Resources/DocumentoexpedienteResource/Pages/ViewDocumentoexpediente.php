<?php

namespace App\Filament\Ayuntamientos\Resources\DocumentoexpedienteResource\Pages;

use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * Página de consulta de documentos de expediente (versión legada).
 *
 * Apunta al recurso DocumentoexpedienteResource del panel «Obras»; se
 * mantiene por compatibilidad y no está registrada en el recurso actual
 * del panel de ayuntamientos.
 */
class ViewDocumentoexpediente extends ViewRecord
{
    protected static string $resource = DocumentoexpedienteResource::class;
}
