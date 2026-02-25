<?php

namespace App\Filament\Resources\TablaDeCarreteras\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TablaDeCarreteras\TablaDeCarreteraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTablaDeCarretera extends EditRecord
{
    protected static string $resource = TablaDeCarreteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
