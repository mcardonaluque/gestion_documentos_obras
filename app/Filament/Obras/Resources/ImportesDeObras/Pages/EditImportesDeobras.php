<?php

namespace App\Filament\Obras\Resources\ImportesDeObras\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\ImportesDeObras\ImportesDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportesDeobras extends EditRecord
{
    protected static string $resource = ImportesDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
