<?php

namespace App\Filament\Obras\Resources\ObrasCedidas\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\ObrasCedidas\ObrasCedidasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar del recurso esqueleto de obras cedidas (sin lógica propia). */
class ListObrasCedidas extends ListRecords
{
    protected static string $resource = ObrasCedidasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
