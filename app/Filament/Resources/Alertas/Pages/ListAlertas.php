<?php

namespace App\Filament\Resources\Alertas\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Alertas\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlertas extends ListRecords
{
    protected static string $resource = AlertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
