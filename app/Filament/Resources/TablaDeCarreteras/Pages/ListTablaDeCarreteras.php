<?php

namespace App\Filament\Resources\TablaDeCarreteras\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeCarreteras\TablaDeCarreteraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TablaDeCarreteraResource (panel de administración). */
class ListTablaDeCarreteras extends ListRecords
{
    protected static string $resource = TablaDeCarreteraResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
