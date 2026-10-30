<?php

namespace App\Filament\Obras\Resources\Proyectos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use Filament\Resources\Pages\EditRecord;

/**
 * Página de edición de proyectos.
 *
 * Garantiza que 'expediente_id' esté informado (lo toma del registro si
 * falta) y aplica la preparación/validación financiera del recurso antes
 * de guardar.
 */
class EditProyecto extends EditRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Completa el expediente_id desde el registro si llega vacío.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['expediente_id'] ?? null) === null || ($data['expediente_id'] ?? null) === '' || ($data['expediente_id'] ?? null) === '0' || ($data['expediente_id'] ?? null) === 0) {
            $data['expediente_id'] = $this->getRecord()->expediente_id;
        }

        return $data;
    }

    /**
     * Completa el expediente_id si falta y recalcula/valida los importes
     * antes de guardar.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['expediente_id'] ?? null) === null || ($data['expediente_id'] ?? null) === '' || ($data['expediente_id'] ?? null) === '0' || ($data['expediente_id'] ?? null) === 0) {
            $data['expediente_id'] = $this->getRecord()->expediente_id;
        }

        return ProyectoResource::prepareFinancialDataBeforeSave($data);
    }
}
