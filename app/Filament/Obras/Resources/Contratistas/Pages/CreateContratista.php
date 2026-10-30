<?php

namespace App\Filament\Obras\Resources\Contratistas\Pages;

use App\Filament\Obras\Resources\Contratistas\ContratistaResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página de creación de contratistas.
 *
 * Antes de crear asigna el siguiente código disponible según el tipo de
 * contratista y, tras crear, actualiza el último código usado del tipo.
 */
class CreateContratista extends CreateRecord
{
    protected static string $resource = ContratistaResource::class;

    /**
     * Asigna el siguiente código de contratista disponible para el tipo
     * seleccionado antes de crear el registro.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tipo = $data['Tipo_contratista'] ?? null;

        $codigoDisponible = ContratistaResource::getSiguienteCodigoDisponiblePorTipo($tipo);

        if (filled($codigoDisponible)) {
            $data['Codigo_contratista'] = $codigoDisponible;
        }

        return $data;
    }

    /**
     * Actualiza el último código usado del tipo tras crear el contratista.
     */
    protected function afterCreate(): void
    {
        ContratistaResource::actualizarUltimoCodigoTipo(
            $this->record->Tipo_contratista,
            $this->record->Codigo_contratista
        );
    }
}
