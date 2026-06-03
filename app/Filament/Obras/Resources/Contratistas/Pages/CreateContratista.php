<?php

namespace App\Filament\Obras\Resources\Contratistas\Pages;

use App\Filament\Obras\Resources\Contratistas\ContratistaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContratista extends CreateRecord
{
    protected static string $resource = ContratistaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tipo = $data['Tipo_contratista'] ?? null;

        $codigoDisponible = ContratistaResource::getSiguienteCodigoDisponiblePorTipo($tipo);

        if (filled($codigoDisponible)) {
            $data['Codigo_contratista'] = $codigoDisponible;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        ContratistaResource::actualizarUltimoCodigoTipo(
            $this->record->Tipo_contratista,
            $this->record->Codigo_contratista
        );
    }
}
