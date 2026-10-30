<?php

namespace App\Filament\Resources\DestinoDeDocumentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DestinoDeDocumentos\DestinoDeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de DestinoDeDocumentosResource (panel de administración). */
class EditDestinoDeDocumentos extends EditRecord
{
    protected static string $resource = DestinoDeDocumentosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
