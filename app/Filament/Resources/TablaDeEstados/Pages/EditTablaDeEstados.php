<?php

namespace App\Filament\Resources\TablaDeEstados\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TablaDeEstados\TablaDeEstadosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTablaDeEstados extends EditRecord
{
    protected static string $resource = TablaDeEstadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
