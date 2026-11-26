<?php

namespace App\Filament\Obras\Resources\Documentoexpedientes\Pages;

use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use App\Models\DocumentoGenerico;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\Url;

/**
 * Listado de documentos de expediente, opcionalmente acotado a una fase
 * documental con «?fase=» (p. ej. menú «Justificación → Documentos de Justificación»).
 */
class ListDocumentoexpedientes extends ListRecords
{
    protected static string $resource = DocumentoexpedienteResource::class;

    /**
     * Fase documental de la URL. Se guarda en el componente para que el filtro
     * se mantenga al paginar, buscar u ordenar (peticiones Livewire sin «?fase»).
     */
    #[Url]
    public ?string $fase = null;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder | Relation | null
    {
        $query = parent::getTableQuery();
        $phase = DocumentoGenerico::normalizePhase($this->fase);

        if ($query instanceof Builder && $phase !== null) {
            $query->whereHas('tipodocumentos', fn (Builder $tipos): Builder => $tipos->forPhase($phase));
        }

        return $query;
    }
}
