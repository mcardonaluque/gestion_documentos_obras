<?php

namespace App\Filament\Resources\DestinoDeDocumentosResource\Pages;

use App\Filament\Resources\DestinoDeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDestinoDeDocumentos extends EditRecord
{
    protected static string $resource = DestinoDeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
