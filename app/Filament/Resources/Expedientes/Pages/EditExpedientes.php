<?php

namespace App\Filament\Resources\Expedientes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Expedientes\ExpedientesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de ExpedientesResource (panel de administración). */
class EditExpedientes extends EditRecord
{
    protected static string $resource = ExpedientesResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
