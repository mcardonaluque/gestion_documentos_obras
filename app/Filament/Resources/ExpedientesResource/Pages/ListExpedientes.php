<?php

namespace App\Filament\Resources\ExpedientesResource\Pages;

use App\Filament\Resources\ExpedientesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpedientes extends ListRecords
{
    protected static string $resource = ExpedientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
