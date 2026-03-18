<?php

namespace App\Filament\Obras\Resources\ActasDeRecepcion\Pages;

use App\Filament\Obras\Resources\ActasDeRecepcion\ActaderecepcionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActaderecepcion extends EditRecord
{
    protected static string $resource = ActaderecepcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
