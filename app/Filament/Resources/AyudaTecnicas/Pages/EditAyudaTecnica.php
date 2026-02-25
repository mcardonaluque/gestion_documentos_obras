<?php

namespace App\Filament\Resources\AyudaTecnicas\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AyudaTecnicas\AyudaTecnicaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAyudaTecnica extends EditRecord
{
    protected static string $resource = AyudaTecnicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
