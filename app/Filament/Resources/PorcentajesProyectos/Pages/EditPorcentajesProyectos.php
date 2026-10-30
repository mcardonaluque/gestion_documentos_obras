<?php

namespace App\Filament\Resources\PorcentajesProyectos\Pages;

use App\Filament\Resources\PorcentajesProyectos\PorcentajesProyectosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de PorcentajesProyectosResource (panel de administración). */
class EditPorcentajesProyectos extends EditRecord
{
    protected static string $resource = PorcentajesProyectosResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
