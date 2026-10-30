<?php

namespace App\Filament\Resources\OrganismoResource\Pages;

use App\Filament\Resources\OrganismoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de OrganismoResource (panel de administración). */
class ListOrganismos extends ListRecords
{
    protected static string $resource = OrganismoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
