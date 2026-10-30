<?php

namespace App\Filament\Resources\FaseDocumentos\Pages;

use App\Filament\Resources\FaseDocumentos\FaseDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página de creación de registros de FaseDocumentoResource (panel de administración). */
class CreateFaseDocumento extends CreateRecord
{
    protected static string $resource = FaseDocumentoResource::class;
}
