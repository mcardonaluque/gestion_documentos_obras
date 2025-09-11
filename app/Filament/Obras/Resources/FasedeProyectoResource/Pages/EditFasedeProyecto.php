<?php

namespace App\Filament\Obras\Resources\FasedeProyectoResource\Pages;

use App\Filament\Obras\Resources\FasedeProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFasedeProyecto extends EditRecord
{
    protected static string $resource = FasedeProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
