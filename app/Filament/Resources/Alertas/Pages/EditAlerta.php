<?php

namespace App\Filament\Resources\Alertas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Alertas\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlerta extends EditRecord
{
    protected static string $resource = AlertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
