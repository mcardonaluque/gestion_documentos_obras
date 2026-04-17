<?php

namespace App\Filament\Obras\Resources\Proyectos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use Filament\Resources\Pages\EditRecord;

class EditProyecto extends EditRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['expediente_id'] ?? null) === null || ($data['expediente_id'] ?? null) === '' || ($data['expediente_id'] ?? null) === '0' || ($data['expediente_id'] ?? null) === 0) {
            $data['expediente_id'] = $this->getRecord()->expediente_id;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['expediente_id'] ?? null) === null || ($data['expediente_id'] ?? null) === '' || ($data['expediente_id'] ?? null) === '0' || ($data['expediente_id'] ?? null) === 0) {
            $data['expediente_id'] = $this->getRecord()->expediente_id;
        }

        return ProyectoResource::prepareFinancialDataBeforeSave($data);
    }
}
