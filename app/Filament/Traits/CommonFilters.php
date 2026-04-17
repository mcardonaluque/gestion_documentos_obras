<?php
// app/Filament/Traits/CommonFilters.php

namespace App\Filament\Traits;  // ← Namespace específico para traits
use App\Models\Planes;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DatosDeInicioDeObras;
use Filament\Tables\Filters\Filter;

/**
 * Trait con filtros comunes reutilizables en listados Filament de obras y expedientes.
 *
 * Centraliza criterios frecuentes como plan, referencia, subreferencia, año de
 * ejecución y expediente, reduciendo duplicidad entre Resources.
 */
trait CommonFilters
{
    /**
     * Devuelve la colección de filtros base que pueden compartirse entre Resources.
     *
     * @return array<int, \Filament\Tables\Filters\BaseFilter>
     */
    public static function getCommonFilters(): array
    {
        return [
            SelectFilter::make('Codigo_Plan')
                ->label('Plan')
                ->searchable()
                ->preload()
                ->optionsLimit(500)
                //->relationship('planes', 'denominacion_plan')
                /*->getOptionLabelFromRecordUsing(fn ($record) =>
                    //$plan = \App\Models\Planes::where('codigo_plan', $value)->first();
                    "{$record->codigo_plan} - {$record->denominacion_plan}"
                )*/
                ->options(
                    Planes::all()
                        ->mapWithKeys(fn ($record) => [
                            $record->codigo_plan => "{$record->codigo_plan} - {$record->denominacion_plan}",
                        ])
                        ->toArray()
                ),
            Filter::make('busqueda')
                ->schema([
                  TextInput::make('numero_obra')
                    ->label('Referencia'),
                  TextInput::make('subreferencia')
                    ->label('Subreferencia'),
                ])  ->columns(2)
                ->query(function (Builder $query, array $data) {

                    return $query
                        ->when($data['numero_obra'], fn($query) => $query->where('numero_obra', '=', $data['numero_obra']))
                        ->when($data['subreferencia'], fn($query) => $query->where('subreferencia', '=', $data['subreferencia']));

                    }),
            SelectFilter::make('ao_ejecucion')
            ->options(function () {
                // Obtener años únicos de la base de datos
                return DatosDeInicioDeObras::query()
                    ->select('ao_ejecucion')
                    ->distinct()
                    ->whereNotNull('ao_ejecucion')
                    ->orderBy('ao_ejecucion', 'desc')
                    ->pluck('ao_ejecucion', 'ao_ejecucion')
                    ->toArray();
                })
                ->label('Año de Ejecución'),

                SelectFilter::make('Expediente')

                ->optionsLimit(500)
                ->label('Expediente')
                ->relationship( 'expediente', 'expediente_id',modifyQueryUsing: function (Builder $query) {
                    $query->select('expediente_id')
                    ->orderBy('ao_ejecucion', 'desc')
                    ->limit(500); // TOP 500
                } ),

            ];
    }
}
