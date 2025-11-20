<?php

namespace App\Filament\Resources\TablaDeCarreteraResource\Pages;

use App\Filament\Resources\TablaDeCarreteraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeCarreteras extends ListRecords
{
    protected static string $resource = TablaDeCarreteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
