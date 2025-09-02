<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObrasResource\Pages;

use App\Filament\Obras\Resources\DatosEjecucionObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab as ComponentsTab;
use Illuminate\Database\Eloquent\Builder;

class ListDatosEjecucionObras extends ListRecords
{
    protected static string $resource = DatosEjecucionObrasResource::class;
    public function getTabs(): array
    {
        $añoActual=now()->year;
        $añoAnterior = now()->subYear(1)->year;
        $añoAnterior2 = now()->subYear(2)->year;
        return [
            $añoAnterior2 => ComponentsTab::make('Año ' . $añoAnterior2)
                ->query(fn (Builder $query) => $query->where('ao_ejecucion', $añoAnterior2)),
            $añoAnterior => ComponentsTab::make('Año ' . $añoAnterior)
                ->query(fn (Builder $query) => $query->where('ao_ejecucion', $añoAnterior)),        
            $añoActual => ComponentsTab::make('Año ' . $añoActual)
                ->query(fn (Builder $query) => $query->where('ao_ejecucion', $añoActual)),    
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
}
