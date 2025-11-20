<?php

namespace App\Filament\Resources\TBEstadosdeDocumentosResource\Pages;

use App\Filament\Resources\TBEstadosdeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTBEstadosdeDocumentos extends EditRecord
{
    protected static string $resource = TBEstadosdeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
