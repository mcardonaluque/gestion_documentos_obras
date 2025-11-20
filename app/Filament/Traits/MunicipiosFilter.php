<?php
// app/Filament/Traits/MunicipioFilters.php

namespace App\Filament\Traits;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DatosDeInicioDeObras;
use App\Models\Municipio;
use App\Models\TablaDeMunicipio;
use Filament\Tables\Filters\Filter;
use function PHPUnit\Framework\isNull;

trait MunicipiosFilter
{
    public static function getMunicipioFromInicioObrasFilter(): SelectFilter
    {
        return SelectFilter::make('municipio')
            ->label('Municipio')
            ->searchable()
            ->preload()
            ->options(function () {
                // Cargar municipios desde DatosDeInicioDeObras
               
                return TablaDeMunicipio::whereHas('obras')
                    ->pluck('nombre_municipio', 'codigo_municipio')
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
                                        
                    return $query->whereHas('municipios', fn($q) => $q->where('codigo_municipio', $state));
                } 
                elseif (method_exists($modelClass, 'obra')) {
                    // Para DatosEjecucionObras - a través de la relación
                    return $query->whereHas('obra.municipios', fn($q) => $q->where('codigo_municipio', $state));
                }
                elseif (method_exists($modelClass, 'municipios')) {
                    // Para modelos con relación directa a municipios
                    return $query->whereHas('municipios', fn($q) => $q->where('codigo_municipio', $state));
                }
                
            });
    }
}