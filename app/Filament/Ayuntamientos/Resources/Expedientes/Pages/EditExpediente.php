<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de expedientes (no registrada actualmente en las rutas del recurso). */
class EditExpediente extends EditRecord
{
    protected static string $resource = ExpedienteResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
