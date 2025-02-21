<?php

namespace App\Filament\Resources\AlertaResource\Pages;

use App\Filament\Resources\AlertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlertas extends ListRecords
{
    protected static string $resource = AlertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
