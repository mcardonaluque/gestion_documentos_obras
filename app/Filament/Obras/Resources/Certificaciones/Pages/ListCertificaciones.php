<?php

namespace App\Filament\Obras\Resources\Certificaciones\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\Certificaciones\CertificacionesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCertificaciones extends ListRecords
{
    protected static string $resource = CertificacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
