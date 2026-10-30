<?php

namespace App\Filament\Resources\Roles\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de RoleResource (panel de administración). */
class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
