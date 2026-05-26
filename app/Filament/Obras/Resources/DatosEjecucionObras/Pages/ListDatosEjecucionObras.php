<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Obras\Resources\DatosEjecucionObras\DatosEjecucionObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDatosEjecucionObras extends ListRecords
{
    protected static string $resource = DatosEjecucionObrasResource::class;
    public function getTabs(): array
    {
        // Usar la misma query base del Resource para que badges y listado sean coherentes.
        $baseQuery = DatosEjecucionObrasResource::getEloquentQuery();

        $años = (clone $baseQuery)
            ->select('ao_ejecucion')
            ->whereNotNull('ao_ejecucion')
            ->distinct()
            ->orderBy('ao_ejecucion', 'desc')
            ->pluck('ao_ejecucion')
            ->filter(fn ($año) => filled($año))
            ->map(fn ($año) => (string) $año)
            ->values();

        $Tabs['all'] = Tab::make('Todos')
            ->label('Todos')
            ->icon('heroicon-o-rectangle-stack')
            ->badge((clone $baseQuery)->count())
            ->query(fn (Builder $query) => $query);

        foreach ($años as $año) {
            $count = (clone $baseQuery)
                ->where('ao_ejecucion', $año)
                ->count();
            $Tabs[] = Tab::make('Año ' . $año)
                ->label((string) $año)
                ->badge($count)
                ->query(fn (Builder $query) => $query->where('ao_ejecucion', $año));
        }
        return $Tabs;
            /*$añoAnterior2 => ComponentsTab::make('Año ' . $añoAnterior2)
                ->query(fn (Builder $query) => $query->where('ao_ejecucion', $añoAnterior2)),
            $añoAnterior => ComponentsTab::make('Año ' . $añoAnterior)
                ->query(fn (Builder $query) => $query->where('ao_ejecucion', $añoAnterior)),
            $añoActual => ComponentsTab::make('Año ' . $añoActual)
                ->query(fn (Builder $query) => $query->where('ao_ejecucion', $añoActual)),   */

    }
    protected function getHeaderActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
}
