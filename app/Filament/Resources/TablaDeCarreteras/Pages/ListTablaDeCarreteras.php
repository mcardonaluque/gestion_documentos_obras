<?php

namespace App\Filament\Resources\TablaDeCarreteras\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TablaDeCarreteras\TablaDeCarreteraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTablaDeCarreteras extends ListRecords
{
    protected static string $resource = TablaDeCarreteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
