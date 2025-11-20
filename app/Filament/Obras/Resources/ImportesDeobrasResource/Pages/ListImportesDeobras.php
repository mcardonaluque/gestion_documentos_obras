<?php

namespace App\Filament\Obras\Resources\ImportesDeObrasResource\Pages;

use App\Filament\Obras\Resources\ImportesDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImportesDeobras extends ListRecords
{
    protected static string $resource = ImportesDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
