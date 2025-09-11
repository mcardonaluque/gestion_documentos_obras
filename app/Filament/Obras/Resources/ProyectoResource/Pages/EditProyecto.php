<?php

namespace App\Filament\Obras\Resources\ProyectoResource\Pages;

use App\Filament\Obras\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProyecto extends EditRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
