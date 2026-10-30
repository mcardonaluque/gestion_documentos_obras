<?php

namespace App\Filament\Resources\TecnicoObras\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TecnicoObras\TecnicoObraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TecnicoObraResource (panel de administración). */
class EditTecnicoObra extends EditRecord
{
    protected static string $resource = TecnicoObraResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
