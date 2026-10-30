<?php

namespace App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages;

use App\Filament\Obras\Resources\ObrasPendientesProyecto\ObrasPendientesProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/** Vista estándar de una obra pendiente de proyecto (sin lógica propia). */
class ViewObrasPendientesProyecto extends ViewRecord
{
    protected static string $resource = ObrasPendientesProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
