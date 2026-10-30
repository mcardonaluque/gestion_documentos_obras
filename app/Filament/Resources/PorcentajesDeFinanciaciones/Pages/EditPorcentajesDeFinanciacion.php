<?php

namespace App\Filament\Resources\PorcentajesDeFinanciaciones\Pages;

use App\Filament\Resources\PorcentajesDeFinanciaciones\PorcentajesDeFinanciacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de PorcentajesDeFinanciacionResource (panel de administración). */
class EditPorcentajesDeFinanciacion extends EditRecord
{
    protected static string $resource = PorcentajesDeFinanciacionResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
