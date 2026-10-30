<?php

namespace App\Filament\Resources\AyudaTecnicas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AyudaTecnicas\AyudaTecnicaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de AyudaTecnicaResource (panel de administración). */
class EditAyudaTecnica extends EditRecord
{
    protected static string $resource = AyudaTecnicaResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
