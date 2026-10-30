<?php

namespace App\Filament\Resources\FaseDocumentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FaseDocumentos\FaseDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de FaseDocumentoResource (panel de administración). */
class EditFaseDocumento extends EditRecord
{
    protected static string $resource = FaseDocumentoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
