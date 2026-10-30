<?php

namespace App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages;

use App\Filament\Obras\Resources\ObrasPendientesProyecto\ObrasPendientesProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página estándar de edición de obras pendientes de proyecto (sin lógica propia). */
class EditObrasPendientesProyecto extends EditRecord
{
    protected static string $resource = ObrasPendientesProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
