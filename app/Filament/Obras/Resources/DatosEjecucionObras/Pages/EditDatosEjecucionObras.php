<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\DatosEjecucionObras\DatosEjecucionObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página estándar de edición de datos de ejecución de obras (sin lógica propia). */
class EditDatosEjecucionObras extends EditRecord
{
    protected static string $resource = DatosEjecucionObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
