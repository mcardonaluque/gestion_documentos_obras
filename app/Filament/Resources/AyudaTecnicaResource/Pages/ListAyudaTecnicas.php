<?php

namespace App\Filament\Resources\AyudaTecnicaResource\Pages;

use App\Filament\Resources\AyudaTecnicaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAyudaTecnicas extends ListRecords
{
    protected static string $resource = AyudaTecnicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
