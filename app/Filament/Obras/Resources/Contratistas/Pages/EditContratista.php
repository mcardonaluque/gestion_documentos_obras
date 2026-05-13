<?php

namespace App\Filament\Obras\Resources\Contratistas\Pages;

use App\Filament\Obras\Resources\Contratistas\ContratistaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContratista extends EditRecord
{
    protected static string $resource = ContratistaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        ContratistaResource::actualizarUltimoCodigoTipo(
            $this->record->Tipo_contratista,
            $this->record->Codigo_contratista
        );
    }
}
