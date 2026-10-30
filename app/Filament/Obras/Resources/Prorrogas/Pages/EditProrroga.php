<?php

namespace App\Filament\Obras\Resources\Prorrogas\Pages;

use App\Filament\Obras\Resources\Prorrogas\ProrrogaResource;
use Filament\Resources\Pages\EditRecord;

/** Página de edición del expediente que aloja el relation manager de prórrogas (sin lógica propia). */
class EditProrroga extends EditRecord
{
    protected static string $resource = ProrrogaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
