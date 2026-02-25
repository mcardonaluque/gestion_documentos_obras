<?php

namespace App\Filament\Resources\Expedientes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Expedientes\ExpedientesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpedientes extends ListRecords
{
    protected static string $resource = ExpedientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
