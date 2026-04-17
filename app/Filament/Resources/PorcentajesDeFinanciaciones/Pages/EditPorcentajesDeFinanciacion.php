<?php

namespace App\Filament\Resources\PorcentajesDeFinanciaciones\Pages;

use App\Filament\Resources\PorcentajesDeFinanciaciones\PorcentajesDeFinanciacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPorcentajesDeFinanciacion extends EditRecord
{
    protected static string $resource = PorcentajesDeFinanciacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
