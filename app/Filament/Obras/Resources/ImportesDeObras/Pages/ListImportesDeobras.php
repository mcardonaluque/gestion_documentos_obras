<?php

namespace App\Filament\Obras\Resources\ImportesDeObras\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\ImportesDeObras\ImportesDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
