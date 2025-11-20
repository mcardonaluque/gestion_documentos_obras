<?php

namespace App\Filament\Resources\TablaDeDepartamentoResource\Pages;

use App\Filament\Resources\TablaDeDepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTablaDeDepartamento extends EditRecord
{
    protected static string $resource = TablaDeDepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
