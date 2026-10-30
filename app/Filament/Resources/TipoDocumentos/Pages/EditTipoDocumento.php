<?php

namespace App\Filament\Resources\TipoDocumentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TipoDocumentos\TipoDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TipoDocumentoResource (panel de administración). */
class EditTipoDocumento extends EditRecord
{
    protected static string $resource = TipoDocumentoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
