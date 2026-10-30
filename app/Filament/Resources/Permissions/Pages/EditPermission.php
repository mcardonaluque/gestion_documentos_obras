<?php

namespace App\Filament\Resources\Permissions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de PermissionResource (panel de administración). */
class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
