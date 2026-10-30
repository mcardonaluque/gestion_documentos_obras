<?php

namespace App\Filament\Resources\TipoDocumentos\Pages;

use App\Filament\Resources\TipoDocumentos\TipoDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de TipoDocumentoResource (panel de administración). */
class CreateTipoDocumento extends CreateRecord
{
    protected static string $resource = TipoDocumentoResource::class;
}
