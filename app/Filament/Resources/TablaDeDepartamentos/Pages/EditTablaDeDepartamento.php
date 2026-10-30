<?php

namespace App\Filament\Resources\TablaDeDepartamentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TablaDeDepartamentos\TablaDeDepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de registros de TablaDeDepartamentoResource (panel de administración). */
class EditTablaDeDepartamento extends EditRecord
{
    protected static string $resource = TablaDeDepartamentoResource::class;

    /** Acciones de cabecera de la página. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
