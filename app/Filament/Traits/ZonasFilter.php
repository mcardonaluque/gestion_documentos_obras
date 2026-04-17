<?php
// app/Filament/Traits/ZonasFilters.php

namespace App\Filament\Traits;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DatosDeInicioDeObras;

use App\Models\Zona;

use function PHPUnit\Framework\isNull;

/**
 * Trait reusable para filtrar listados por zona geográfica.
 *
 * Permite aplicar la misma lógica de filtrado sobre modelos con relación directa
 * a municipios o a través de una obra asociada.
 */
trait ZonasFilter
{
    /**
     * Construye el filtro select de zona a partir de la relación municipios.zonas.
     */
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
