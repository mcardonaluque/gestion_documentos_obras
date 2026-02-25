<?php

namespace App\Filament\Obras\Resources\Avisos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\Avisos\AvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAviso extends EditRecord
{
    protected static string $resource = AvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
