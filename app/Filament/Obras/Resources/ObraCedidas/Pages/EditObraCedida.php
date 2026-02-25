<?php

namespace App\Filament\Obras\Resources\ObraCedidas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\ObraCedidas\ObraCedidaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObraCedida extends EditRecord
{
    protected static string $resource = ObraCedidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
