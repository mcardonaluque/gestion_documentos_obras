<?php

namespace App\Filament\Obras\Resources\ExpedienteResource\Pages;

use App\Filament\Obras\Resources\ExpedienteResource;
use App\Models\DocumentoExpediente;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpedientes extends ListRecords
{
    protected static string $resource = ExpedienteResource::class;
    public ?string $expedienteSeleccionado = null;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public array $expandedRecords = [];

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
protected function getRelationManagers(): array
{
    // Solo mostrar el Relation Manager si hay un expediente seleccionado
    if (!$this->expedienteSeleccionado) {
        return [];
    }

    return parent::getRelationManagers();
}

// Obtener el expediente seleccionado para el Relation Manager
public function getSelectedRecord(): ?DocumentoExpediente
{
    if (!$this->expedienteSeleccionado) {
        return null;
    }

    return $this->getResource()::getModel()::find($this->expedienteSeleccionado);
}

// Método para manejar la selección desde la tabla
public function seleccionarExpediente($expedienteId): void
{
    $this->expedienteSeleccionado = $expedienteId;
    
    // Forzar la actualización del Relation Manager
    $this->dispatch('refreshRelationManager');
}
}
