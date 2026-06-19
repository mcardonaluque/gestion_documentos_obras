<?php

namespace App\Filament\Resources\FechaHitoCatalogos\Pages;

use App\Filament\Resources\FechaHitoCatalogos\FechaHitoCatalogoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFechaHitoCatalogos extends ListRecords
{
    protected static string $resource = FechaHitoCatalogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
