<?php

declare(strict_types=1);

namespace App\Filament\Resources\PlazoObraActivos\Pages;

use App\Filament\Resources\PlazoObraActivos\PlazoObraActivoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de PlazoObraActivoResource (panel de administración). */
class ListPlazoObraActivos extends ListRecords
{
    protected static string $resource = PlazoObraActivoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
