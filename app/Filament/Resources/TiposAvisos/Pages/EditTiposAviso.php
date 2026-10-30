<?php

namespace App\Filament\Resources\TiposAvisos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TiposAvisos\TiposAvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TiposAvisoResource (panel de administración). */
class EditTiposAviso extends EditRecord
{
    protected static string $resource = TiposAvisoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
