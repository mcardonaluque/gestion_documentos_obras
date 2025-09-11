<?php

namespace App\Filament\Obras\Resources\FasedeProyectoResource\Pages;

use App\Filament\Obras\Resources\FasedeProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFasedeProyectos extends ListRecords
{
    protected static string $resource = FasedeProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
