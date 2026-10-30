<?php

namespace App\Filament\Resources\Permissions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de registros de PermissionResource (panel de administración). */
class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
