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
            Actions\Action::make('volver_a_tabla')
                ->label('Volver a contratistas')
                ->url(ContratistaResource::getUrl('index')),
            Actions\Action::make('anadir_otro')
                ->label('Anadir otro contratista')
                ->url(ContratistaResource::getUrl('create')),
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
