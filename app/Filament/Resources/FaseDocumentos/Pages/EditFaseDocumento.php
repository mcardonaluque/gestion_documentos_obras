<?php

namespace App\Filament\Resources\FaseDocumentos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FaseDocumentos\FaseDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaseDocumento extends EditRecord
{
    protected static string $resource = FaseDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
