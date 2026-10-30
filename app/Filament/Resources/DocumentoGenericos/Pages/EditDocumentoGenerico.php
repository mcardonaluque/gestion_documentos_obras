<?php

namespace App\Filament\Resources\DocumentoGenericos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DocumentoGenericos\DocumentoGenericoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de DocumentoGenericoResource (panel de administración). */
class EditDocumentoGenerico extends EditRecord
{
    protected static string $resource = DocumentoGenericoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
