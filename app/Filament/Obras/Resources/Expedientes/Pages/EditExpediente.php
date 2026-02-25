<?php

namespace App\Filament\Obras\Resources\Expedientes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\Expedientes\ExpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpediente extends EditRecord
{
    protected static string $resource = ExpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
