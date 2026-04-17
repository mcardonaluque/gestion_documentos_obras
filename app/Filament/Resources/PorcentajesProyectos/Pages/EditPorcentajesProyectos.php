<?php

namespace App\Filament\Resources\PorcentajesProyectos\Pages;

use App\Filament\Resources\PorcentajesProyectos\PorcentajesProyectosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPorcentajesProyectos extends EditRecord
{
    protected static string $resource = PorcentajesProyectosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
