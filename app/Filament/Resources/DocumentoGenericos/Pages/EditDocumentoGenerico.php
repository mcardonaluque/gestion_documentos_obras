<?php

namespace App\Filament\Resources\DocumentoGenericos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DocumentoGenericos\DocumentoGenericoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoGenerico extends EditRecord
{
    protected static string $resource = DocumentoGenericoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
