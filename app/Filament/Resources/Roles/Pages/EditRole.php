<?php

namespace App\Filament\Resources\Roles\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de RoleResource (panel de administración). */
class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
