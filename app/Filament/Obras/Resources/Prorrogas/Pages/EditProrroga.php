<?php

namespace App\Filament\Obras\Resources\Prorrogas\Pages;

use App\Filament\Obras\Resources\Prorrogas\ProrrogaResource;
use Filament\Resources\Pages\EditRecord;

class EditProrroga extends EditRecord
{
    protected static string $resource = ProrrogaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
