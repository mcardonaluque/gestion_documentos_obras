<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObrasResource\Pages;

use App\Filament\Obras\Resources\DatosEjecucionObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatosEjecucionObras extends EditRecord
{
    protected static string $resource = DatosEjecucionObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
