<?php

namespace App\Filament\Resources\TBEstadosdeDocumentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TBEstadosdeDocumentos\TBEstadosdeDocumentosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTBEstadosdeDocumentos extends EditRecord
{
    protected static string $resource = TBEstadosdeDocumentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
