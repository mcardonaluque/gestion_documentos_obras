<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use Filament\Resources\Pages\ListRecords;

class ListExpedientes extends ListRecords
{
    protected static string $resource = ExpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
