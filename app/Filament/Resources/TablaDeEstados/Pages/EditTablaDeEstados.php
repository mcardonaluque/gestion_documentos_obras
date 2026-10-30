<?php

namespace App\Filament\Resources\TablaDeEstados\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TablaDeEstados\TablaDeEstadosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TablaDeEstadosResource (panel de administración). */
class EditTablaDeEstados extends EditRecord
{
    protected static string $resource = TablaDeEstadosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
