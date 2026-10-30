<?php

namespace App\Filament\Resources\TipoContratistas\Pages;

use App\Filament\Resources\TipoContratistas\TipoContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TipoContratistaResource (panel de administración). */
class EditTipoContratista extends EditRecord
{
    protected static string $resource = TipoContratistaResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
