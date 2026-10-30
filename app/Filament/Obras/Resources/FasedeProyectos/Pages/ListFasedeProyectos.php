<?php

namespace App\Filament\Obras\Resources\FasedeProyectos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\FasedeProyectos\FasedeProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar de fases de proyecto (sin lógica propia). */
class ListFasedeProyectos extends ListRecords
{
    protected static string $resource = FasedeProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
