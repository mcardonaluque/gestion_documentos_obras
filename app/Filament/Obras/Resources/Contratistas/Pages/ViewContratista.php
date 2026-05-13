<?php

namespace App\Filament\Obras\Resources\Contratistas\Pages;

use App\Filament\Obras\Resources\Contratistas\ContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContratista extends ViewRecord
{
    protected static string $resource = ContratistaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
