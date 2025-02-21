<?php

namespace App\Filament\Obras\Resources\ImportesPorOrganismoResource\Pages;

use App\Filament\Obras\Resources\ImportesPorOrganismoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportesPorOrganismo extends EditRecord
{
    protected static string $resource = ImportesPorOrganismoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
