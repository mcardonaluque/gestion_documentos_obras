<?php

namespace App\Filament\Obras\Resources\ExpedienteResource\Pages;

use App\Filament\Obras\Resources\ExpedienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpediente extends EditRecord
{
    protected static string $resource = ExpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
