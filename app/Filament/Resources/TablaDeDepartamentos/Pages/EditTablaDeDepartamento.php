<?php

namespace App\Filament\Resources\TablaDeDepartamentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TablaDeDepartamentos\TablaDeDepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTablaDeDepartamento extends EditRecord
{
    protected static string $resource = TablaDeDepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
