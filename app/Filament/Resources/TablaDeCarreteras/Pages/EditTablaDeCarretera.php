<?php

namespace App\Filament\Resources\TablaDeCarreteras\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TablaDeCarreteras\TablaDeCarreteraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TablaDeCarreteraResource (panel de administración). */
class EditTablaDeCarretera extends EditRecord
{
    protected static string $resource = TablaDeCarreteraResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
