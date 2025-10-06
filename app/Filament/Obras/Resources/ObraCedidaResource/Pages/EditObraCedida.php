<?php

namespace App\Filament\Obras\Resources\ObraCedidaResource\Pages;

use App\Filament\Obras\Resources\ObraCedidaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObraCedida extends EditRecord
{
    protected static string $resource = ObraCedidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
