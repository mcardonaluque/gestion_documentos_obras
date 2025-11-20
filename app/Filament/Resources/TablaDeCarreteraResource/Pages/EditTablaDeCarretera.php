<?php

namespace App\Filament\Resources\TablaDeCarreteraResource\Pages;

use App\Filament\Resources\TablaDeCarreteraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTablaDeCarretera extends EditRecord
{
    protected static string $resource = TablaDeCarreteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
