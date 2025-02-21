<?php

namespace App\Filament\Obras\Resources\ImportesDeobrasResource\Pages;

use App\Filament\Obras\Resources\ImportesDeobrasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportesDeobras extends EditRecord
{
    protected static string $resource = ImportesDeobrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
