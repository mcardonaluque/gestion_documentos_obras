<?php

namespace App\Filament\Obras\Resources\ImportesDeObras\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\ImportesDeObras\ImportesDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/** Listado estándar de importes de obras (sin lógica propia). */
class ListImportesDeobras extends ListRecords
{
    protected static string $resource = ImportesDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
