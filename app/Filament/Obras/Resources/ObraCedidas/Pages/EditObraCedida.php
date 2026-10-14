<?php

namespace App\Filament\Obras\Resources\ObraCedidas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\ObraCedidas\ObraCedidaResource;
use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
