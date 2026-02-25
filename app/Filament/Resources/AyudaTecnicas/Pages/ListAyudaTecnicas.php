<?php

namespace App\Filament\Resources\AyudaTecnicas\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\AyudaTecnicas\AyudaTecnicaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAyudaTecnicas extends ListRecords
{
    protected static string $resource = AyudaTecnicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
