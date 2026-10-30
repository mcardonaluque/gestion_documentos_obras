<?php

namespace App\Filament\Obras\Resources\Expedientes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\Expedientes\ExpedienteResource;
use App\Models\DocumentoExpediente;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Listado de expedientes con soporte de expansión de filas y selección de
 * expediente para mostrar sus relation managers bajo demanda.
 */
class ListExpedientes extends ListRecords
{
    protected static string $resource = ExpedienteResource::class;
    public ?string $expedienteSeleccionado = null;
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public array $expandedRecords = [];

/**
 * Alterna la expansión/contracción de una fila del listado.
 */
public function toggleRecordExpansion($key): void
{
    //dd($key);
    if (($idx = array_search($key, $this->expandedRecords)) !== false) {
        // quitar
        unset($this->expandedRecords[$idx]);
        // reindexar
        $this->expandedRecords = array_values($this->expandedRecords);
    } else {
        // añadir
        $this->expandedRecords[] = $key;
    }
}
/**
 * Solo muestra los relation managers cuando hay un expediente seleccionado.
 */
protected function getRelationManagers(): array
{
    // Solo mostrar el Relation Manager si hay un expediente seleccionado
    if (!$this->expedienteSeleccionado) {
        return [];
    }

    return parent::getRelationManagers();
}

/**
 * Devuelve el expediente seleccionado para alimentar el relation manager.
 */
public function getSelectedRecord(): ?DocumentoExpediente
{
    if (!$this->expedienteSeleccionado) {
        return null;
    }

    return $this->getResource()::getModel()::find($this->expedienteSeleccionado);
}

/**
 * Registra el expediente seleccionado desde la tabla y refresca el
 * relation manager asociado.
 */
public function seleccionarExpediente($expedienteId): void
{
    $this->expedienteSeleccionado = $expedienteId;
    
    // Forzar la actualización del Relation Manager
    $this->dispatch('refreshRelationManager');
}
}
