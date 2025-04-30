<?php

namespace App\Filament\Resources\TecnicoObraResource\Pages;

use App\Filament\Resources\TecnicoObraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTecnicoObra extends EditRecord
{
    protected static string $resource = TecnicoObraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
