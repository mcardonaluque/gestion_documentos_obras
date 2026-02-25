<?php

namespace App\Filament\Obras\Resources\FasedeProyectos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\FasedeProyectos\FasedeProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
