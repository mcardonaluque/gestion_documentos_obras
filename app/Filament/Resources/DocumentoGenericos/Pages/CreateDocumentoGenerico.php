<?php

namespace App\Filament\Resources\DocumentoGenericos\Pages;

use App\Filament\Resources\DocumentoGenericos\DocumentoGenericoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de DocumentoGenericoResource (panel de administración). */
class CreateDocumentoGenerico extends CreateRecord
{
    protected static string $resource = DocumentoGenericoResource::class;
}
