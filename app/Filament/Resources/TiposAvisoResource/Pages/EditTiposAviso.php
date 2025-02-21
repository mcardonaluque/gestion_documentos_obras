<?php

namespace App\Filament\Resources\TiposAvisoResource\Pages;

use App\Filament\Resources\TiposAvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTiposAviso extends EditRecord
{
    protected static string $resource = TiposAvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
