<?php

namespace App\Filament\Resources\TecnicoObras\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TecnicoObras\TecnicoObraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTecnicoObra extends EditRecord
{
    protected static string $resource = TecnicoObraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
