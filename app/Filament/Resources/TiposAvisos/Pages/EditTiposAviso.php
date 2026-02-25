<?php

namespace App\Filament\Resources\TiposAvisos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TiposAvisos\TiposAvisoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTiposAviso extends EditRecord
{
    protected static string $resource = TiposAvisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
