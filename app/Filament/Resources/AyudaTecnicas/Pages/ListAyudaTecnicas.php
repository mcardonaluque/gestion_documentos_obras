<?php

namespace App\Filament\Resources\AyudaTecnicas\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\AyudaTecnicas\AyudaTecnicaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de AyudaTecnicaResource (panel de administración). */
class ListAyudaTecnicas extends ListRecords
{
    protected static string $resource = AyudaTecnicaResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
