<?php

namespace App\Filament\Resources\OrganismoResource\Pages;

use App\Filament\Resources\OrganismoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de OrganismoResource (panel de administración). */
class EditOrganismo extends EditRecord
{
    protected static string $resource = OrganismoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
