<?php

namespace App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages;

use App\Filament\Obras\Resources\ObrasPendientesProyecto\ObrasPendientesProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

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
