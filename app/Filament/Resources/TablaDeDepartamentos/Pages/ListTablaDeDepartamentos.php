<?php

namespace App\Filament\Resources\TablaDeDepartamentos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeDepartamentos\TablaDeDepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TablaDeDepartamentoResource (panel de administración). */
class ListTablaDeDepartamentos extends ListRecords
{
    protected static string $resource = TablaDeDepartamentoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
