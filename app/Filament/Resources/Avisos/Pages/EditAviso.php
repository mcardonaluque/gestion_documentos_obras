<?php

namespace App\Filament\Resources\Avisos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Avisos\AvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de AvisoResource (panel de administración). */
class EditAviso extends EditRecord
{
    protected static string $resource = AvisoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
