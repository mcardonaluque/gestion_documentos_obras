<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\Pages;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource;
use App\Models\DatosDeInicioDeObras;
use Filament\Actions;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Infolists\Components\Tabs\Tab as TabsTab;
use Filament\Resources\Components\Tab as ComponentsTab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDatosDeInicioDeObras extends ListRecords
{
    public function getTabs(): array
{
    $añoActual=now()->year;
    $añoAnterior = now()->subYear(1)->year;
    $añoAnterior2 = now()->subYear(10)->year;
    $Tabs['all'] = ComponentsTab::make('Todos')
        ->label('Todos')
        ->icon('heroicon-o-rectangle-stack')
        ->badge(DatosDeInicioDeObras::query()->count())
        ->query(fn (Builder $query) => $query);
    foreach (range($añoAnterior2, $añoActual) as $año) {
        // Aquí puedes realizar alguna acción con cada año
        // Por ejemplo, podrías crear una pestaña para cada año
        $count = DatosDeInicioDeObras::where('ao_ejecucion', $año)
                ->where('Codigo_Plan', '!=', '')
                ->count();
        $Tabs[] = ComponentsTab::make('Año ' . $año)
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
    protected static string $resource = DatosDeInicioDeObrasResource::class;
    protected function getHeaderActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
   
}
