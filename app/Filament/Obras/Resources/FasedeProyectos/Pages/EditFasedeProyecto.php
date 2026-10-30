<?php

namespace App\Filament\Obras\Resources\FasedeProyectos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\FasedeProyectos\FasedeProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página estándar de edición de fases de proyecto (sin lógica propia). */
class EditFasedeProyecto extends EditRecord
{
    protected static string $resource = FasedeProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
