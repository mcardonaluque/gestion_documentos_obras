<?php

namespace App\Filament\Resources\DatosDeInicioDeObrasResource\Pages;

use App\Filament\Resources\DatosDeInicioDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatosDeInicioDeObras extends EditRecord
{
    protected static string $resource = DatosDeInicioDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
