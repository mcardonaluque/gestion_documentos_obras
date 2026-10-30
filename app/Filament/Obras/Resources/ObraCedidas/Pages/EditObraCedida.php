<?php

namespace App\Filament\Obras\Resources\ObraCedidas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\ObraCedidas\ObraCedidaResource;
use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/** Página de edición de obras cedidas; valida fechas con NotifiesAndValidatesDates. */
class EditObraCedida extends EditRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = ObraCedidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
