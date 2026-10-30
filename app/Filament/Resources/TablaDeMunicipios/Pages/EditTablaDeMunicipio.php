<?php

namespace App\Filament\Resources\TablaDeMunicipios\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TablaDeMunicipios\TablaDeMunicipioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TablaDeMunicipioResource (panel de administración). */
class EditTablaDeMunicipio extends EditRecord
{
    protected static string $resource = TablaDeMunicipioResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
