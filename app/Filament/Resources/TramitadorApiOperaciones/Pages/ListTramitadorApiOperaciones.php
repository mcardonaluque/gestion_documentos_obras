<?php

declare(strict_types=1);

namespace App\Filament\Resources\TramitadorApiOperaciones\Pages;

use App\Filament\Resources\TramitadorApiOperaciones\TramitadorApiOperacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de TramitadorApiOperacionResource (panel de administración). */
class ListTramitadorApiOperaciones extends ListRecords
{
    protected static string $resource = TramitadorApiOperacionResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
