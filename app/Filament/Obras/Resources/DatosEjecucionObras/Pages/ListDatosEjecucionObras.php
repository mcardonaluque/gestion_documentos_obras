<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Obras\Resources\DatosEjecucionObras\DatosEjecucionObrasResource;
use App\Models\DatosEjecucionObras;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDatosEjecucionObras extends ListRecords
{
    protected static string $resource = DatosEjecucionObrasResource::class;
    public function getTabs(): array
    {
        $añoActual=now()->year;
        $añoAnterior = now()->subYears(1)->year;
        $añoAnterior2 = now()->subYears(10)->year;
        $Tabs['all'] = Tab::make('Todos')
            ->label('Todos')
            ->icon('heroicon-o-rectangle-stack')
            ->badge(DatosEjecucionObras::query()->count())
            ->query(fn (Builder $query) => $query);
        foreach (range($añoAnterior2, $añoActual) as $año) {
            // Aquí puedes realizar alguna acción con cada año
            // Por ejemplo, podrías crear una pestaña para cada año
            $count = DatosEjecucionObras::where('ao_ejecucion', $año)
            //->where('team_id',filament()->getTenant()->id)
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
