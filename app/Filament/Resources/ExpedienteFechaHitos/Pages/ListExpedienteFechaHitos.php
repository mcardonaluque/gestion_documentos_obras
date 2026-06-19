<?php

namespace App\Filament\Resources\ExpedienteFechaHitos\Pages;

use App\Filament\Resources\ExpedienteFechaHitos\ExpedienteFechaHitoResource;
use Filament\Resources\Pages\ListRecords;

class ListExpedienteFechaHitos extends ListRecords
{
    protected static string $resource = ExpedienteFechaHitoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
