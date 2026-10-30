<?php

namespace App\Filament\Resources\TecnicoObras\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TecnicoObras\TecnicoObraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TecnicoObraResource (panel de administración). */
class ListTecnicoObras extends ListRecords
{
    protected static string $resource = TecnicoObraResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
