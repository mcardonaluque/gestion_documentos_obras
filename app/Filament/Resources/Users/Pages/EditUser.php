<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de UserResource (panel de administración). */
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
