<?php

namespace App\Filament\Resources\FechaHitoCatalogos\Pages;

use App\Filament\Resources\FechaHitoCatalogos\FechaHitoCatalogoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de FechaHitoCatalogoResource (panel de administración). */
class ListFechaHitoCatalogos extends ListRecords
{
    protected static string $resource = FechaHitoCatalogoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
