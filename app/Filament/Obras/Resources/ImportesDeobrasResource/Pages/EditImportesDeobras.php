<?php

namespace App\Filament\Obras\Resources\ImportesDeObrasResource\Pages;

use App\Filament\Obras\Resources\ImportesDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportesDeobras extends EditRecord
{
    protected static string $resource = ImportesDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
