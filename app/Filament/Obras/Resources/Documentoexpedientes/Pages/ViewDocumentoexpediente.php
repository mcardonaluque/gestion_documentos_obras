<?php

namespace App\Filament\Obras\Resources\Documentoexpedientes\Pages;

use Filament\Actions\Action;
use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions;
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
    return DocumentoexpedienteResource::getUrl(''); // o la URL que prefieras
}
}
