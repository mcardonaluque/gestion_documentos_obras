<?php

namespace App\Filament\Obras\Resources\Proyectos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProyecto extends EditRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
