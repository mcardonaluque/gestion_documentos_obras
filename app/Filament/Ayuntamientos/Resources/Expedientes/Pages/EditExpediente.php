<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use Filament\Actions\DeleteAction;
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
