<?php

namespace App\Filament\Resources\TBEstadosdeDocumentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TBEstadosdeDocumentos\TBEstadosdeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TBEstadosdeDocumentosResource (panel de administración). */
class EditTBEstadosdeDocumentos extends EditRecord
{
    protected static string $resource = TBEstadosdeDocumentosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
