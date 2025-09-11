<?php

namespace App\Filament\Obras\Resources\CertificacionesResource\Pages;

use App\Filament\Obras\Resources\CertificacionesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCertificaciones extends ListRecords
{
    protected static string $resource = CertificacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
