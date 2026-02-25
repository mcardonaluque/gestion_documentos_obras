<?php

namespace App\Filament\Obras\Resources\ImportesPorOrganismos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\ImportesPorOrganismos\ImportesPorOrganismoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImportesPorOrganismos extends ListRecords
{
    protected static string $resource = ImportesPorOrganismoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
