<?php

namespace App\Filament\Resources\Avisos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Avisos\AvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de AvisoResource (panel de administración). */
class ListAvisos extends ListRecords
{
    protected static string $resource = AvisoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
