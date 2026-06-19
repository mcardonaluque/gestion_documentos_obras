<?php

namespace App\Filament\Resources\FechaHitoCatalogos\Pages;

use App\Filament\Resources\FechaHitoCatalogos\FechaHitoCatalogoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFechaHitoCatalogo extends EditRecord
{
    protected static string $resource = FechaHitoCatalogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
