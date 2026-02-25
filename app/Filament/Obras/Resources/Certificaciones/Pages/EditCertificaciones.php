<?php

namespace App\Filament\Obras\Resources\Certificaciones\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\Certificaciones\CertificacionesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCertificaciones extends EditRecord
{
    protected static string $resource = CertificacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
