<?php

namespace App\Filament\Resources\DestinoDeDocumentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DestinoDeDocumentos\DestinoDeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDestinoDeDocumentos extends EditRecord
{
    protected static string $resource = DestinoDeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
