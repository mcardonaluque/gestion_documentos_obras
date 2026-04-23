<?php

namespace App\Filament\Traits;

use App\Models\DatosDeInicioDeObras;
use App\Models\Expediente;
use App\Models\Planes;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait con filtros comunes reutilizables en listados Filament de obras y expedientes.
 *
 * Adapta automáticamente el nombre de los campos según el modelo del Resource
 * para que la misma colección de filtros funcione tanto en obras como en expedientes.
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
        $modelClass = static::$model ?? DatosDeInicioDeObras::class;
        $planColumn = is_a($modelClass, Expediente::class, true) ? 'codigo_plan' : 'Codigo_Plan';
        $referenceColumn = is_a($modelClass, Expediente::class, true) ? 'referencia' : 'numero_obra';

        return [
            SelectFilter::make($planColumn)
                ->label('Plan')
                ->searchable()
                ->preload()
                ->optionsLimit(500)
                ->options(
                    Planes::query()
                        ->orderBy('codigo_plan')
                        ->get()
                        ->mapWithKeys(fn ($record) => [
                            $record->codigo_plan => "{$record->codigo_plan} - {$record->denominacion_plan}",
                        ])
                        ->toArray()
                ),

            Filter::make('busqueda')
                ->label('Búsqueda rápida')
                ->schema([
                    TextInput::make('referencia')
                        ->label('Referencia'),
                    TextInput::make('subreferencia')
                        ->label('Subreferencia'),
                    TextInput::make('expediente_id')
                        ->label('Expediente'),
                ])
                ->columns(3)
                ->query(function (Builder $query, array $data) use ($referenceColumn): Builder {
                    return $query
                        ->when(filled($data['referencia'] ?? null), fn (Builder $query): Builder => $query->where($referenceColumn, '=', $data['referencia']))
                        ->when(filled($data['subreferencia'] ?? null), fn (Builder $query): Builder => $query->where('subreferencia', '=', $data['subreferencia']))
                        ->when(filled($data['expediente_id'] ?? null), fn (Builder $query): Builder => $query->where('expediente_id', 'like', '%' . $data['expediente_id'] . '%'));
                }),

            SelectFilter::make('ao_ejecucion')
                ->label('Año de Ejecución')
                ->options(function () use ($modelClass): array {
                    return $modelClass::query()
                        ->select('ao_ejecucion')
                        ->distinct()
                        ->whereNotNull('ao_ejecucion')
                        ->orderBy('ao_ejecucion', 'desc')
                        ->pluck('ao_ejecucion', 'ao_ejecucion')
                        ->toArray();
                }),

            SelectFilter::make('expediente_id')
                ->label('Expediente')
                ->searchable()
                ->preload()
                ->optionsLimit(500)
                ->options(function () use ($modelClass): array {
                    return $modelClass::query()
                        ->select('expediente_id', 'ao_ejecucion')
                        ->whereNotNull('expediente_id')
                        ->orderBy('ao_ejecucion', 'desc')
                        ->limit(500)
                        ->get()
                        ->pluck('expediente_id', 'expediente_id')
                        ->toArray();
                }),
        ];
    }
}
