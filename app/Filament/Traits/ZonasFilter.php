<?php
// app/Filament/Traits/ZonasFilters.php

namespace App\Filament\Traits;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DatosDeInicioDeObras;

use App\Models\Zona;
use App\Models\TablaDeMunicipio;

use Filament\Tables\Filters\Filter;
use function PHPUnit\Framework\isNull;

trait ZonasFilter
{
    public static function getZonaFromInicioObrasFilter(): SelectFilter
    {
        return SelectFilter::make('zona')
            ->label('Zona')
            ->searchable()
            ->preload()
            ->options(function () {
                // Cargar zonas desde DatosDeInicioDeObras
               
                return Zona::whereHas('municipio')
                    ->pluck('ZONA', 'CODIGO')
                    ->toArray();
            })
            ->query(function (Builder $query, $state) {
                
                if (blank($state)) {
                    return ;
                }

                $modelClass = static::getModel();
                if (in_Array(null,$state,true)) {
                    return $query;
                };
                // Diferentes estrategias según el modelo
                if ($modelClass === DatosDeInicioDeObras::class) {
                    // Para DatosDeInicioDeObras - relación directa
                                        
                    return $query->whereHas('municipios.zonas', fn($q) => $q->where('CODIGO', $state));
                           
                } 
                elseif (method_exists($modelClass, 'obra')) {
                    // Para DatosEjecucionObras - a través de la relación
                    return $query->whereHas('obra.municipios.zonas', fn($q) => $q->where('CODIGO', $state));
                }
                elseif (method_exists($modelClass, 'municipios')) {
                    // Para modelos con relación directa a zonas
                    dd($modelClass);
                    return $query->whereHas('municipios.zonas', fn($q) => $q->where('CODIGO', $state));
                }
                
            });
    }
}