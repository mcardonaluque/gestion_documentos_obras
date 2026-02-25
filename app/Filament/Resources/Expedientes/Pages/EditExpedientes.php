<?php

namespace App\Filament\Resources\Expedientes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Expedientes\ExpedientesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpedientes extends EditRecord
{
    protected static string $resource = ExpedientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
