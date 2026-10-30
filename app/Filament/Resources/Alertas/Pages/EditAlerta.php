<?php

namespace App\Filament\Resources\Alertas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Alertas\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de AlertaResource (panel de administración). */
class EditAlerta extends EditRecord
{
    protected static string $resource = AlertaResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
