<?php

namespace App\Filament\Obras\Resources\ImportesPorOrganismos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\ImportesPorOrganismos\ImportesPorOrganismoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportesPorOrganismo extends EditRecord
{
    protected static string $resource = ImportesPorOrganismoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
