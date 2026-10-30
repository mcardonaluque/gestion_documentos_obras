<?php

namespace App\Filament\Resources\FechaHitoCatalogos\Pages;

use App\Filament\Resources\FechaHitoCatalogos\FechaHitoCatalogoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de FechaHitoCatalogoResource (panel de administración). */
class EditFechaHitoCatalogo extends EditRecord
{
    protected static string $resource = FechaHitoCatalogoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
