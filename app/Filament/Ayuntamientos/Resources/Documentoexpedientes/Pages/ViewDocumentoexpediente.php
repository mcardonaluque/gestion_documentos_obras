<?php

namespace App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewDocumentoexpediente extends ViewRecord
{
    protected static string $resource = DocumentoexpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelar')
                ->label('Cancelar')
                ->color('gray')
                ->url($this->getRedirectUrl()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return DocumentoexpedienteResource::getUrl('');
    }
}
