<?php

namespace App\Filament\Resources\AyudaTecnicaResource\Pages;

use App\Filament\Resources\AyudaTecnicaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAyudaTecnica extends EditRecord
{
    protected static string $resource = AyudaTecnicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
