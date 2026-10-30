<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo;

use App\Filament\Obras\Resources\ActasDeReplanteo\Pages\CreateActadereplanteo;
use App\Filament\Obras\Resources\ActasDeReplanteo\Pages\EditActadereplanteo;
use App\Filament\Obras\Resources\ActasDeReplanteo\Pages\ListActadereplanteos;
use App\Models\DatosEjecucionObras;
use App\Models\Planes;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Recurso Filament para gestionar las actas de replanteo de obras.
 *
 * Gestiona el modelo {@see \App\Models\actadereplanteo} con slug
 * 'actasdereplanteo' en el grupo de navegación "Ejecucion". Registra las
 * fechas de inicio/final/prórroga del acta y los indicadores de impresión
 * y recepción, con filtros por programa, año de ejecución y búsqueda rápida.
 */
class ActasdereplanteoResource extends Resource
{
    protected static ?string $model = \App\Models\actadereplanteo::class;

    protected static ?string $slug = 'actasdereplanteo';

    protected static ?string $modelLabel = 'Acta de replanteo';

    protected static ?string $pluralModelLabel = 'Actas de replanteo';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = 'Ejecucion';

    /**
     * Define el formulario de creación/edición del acta de replanteo.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->required()
                    ->maxLength(50),
                DateTimePicker::make('Fecha_Inicio_Acta_Replanteo')
                    ->label('Fecha inicio'),
                DateTimePicker::make('Fecha_Final_Acta_Replanteo')
                    ->label('Fecha final'),
                DateTimePicker::make('Fecha_Prorroga_Acta_Replanteo')
                    ->label('Fecha prorroga'),
                Toggle::make('Indicador_Impresion_AR')
                    ->label('Impresion AR'),
                Toggle::make('Indicador_Recepcion_AR')
                    ->label('Recepcion AR'),
                TextInput::make('team_id')
                    ->hidden()
                    ->numeric(),
            ]);
    }

    /**
     * Define la tabla de actas de replanteo, ordenada por año de ejecución
     * descendente y con filtros por programa, año y búsqueda rápida.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('ejecucion.Codigo_Plan')
                    ->label('Programa'),
                TextColumn::make('ejecucion.numero_obra')
                    ->label('Obra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ejecucion.subreferencia')
                    ->label('Subref.')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ejecucion.ao_ejecucion')
                    ->label('Año')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ejecucion.obra.nombre_obra1')
                    ->label('Nombre obra'),
                TextColumn::make('Fecha_Inicio_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Final_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Prorroga_Acta_Replanteo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Indicador_Impresion_AR')
                    ->boolean(),
                IconColumn::make('Indicador_Recepcion_AR')
                    ->boolean(),
            ])
            ->defaultSort(fn (Builder $query): Builder => $query->orderByDesc(
                DatosEjecucionObras::query()
                    ->select('ao_ejecucion')
                    ->whereColumn('Datos_Ejecucion_Obras.expediente_id', 'ActasDeReplanteo.expediente_id')
                    ->limit(1)
            ))
            ->filters([
                SelectFilter::make('programa')
                    ->label('Programa')
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
                    )
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        filled($data['value'] ?? null),
                        fn (Builder $query): Builder => $query->whereHas(
                            'ejecucion',
                            fn (Builder $query): Builder => $query->where('Codigo_Plan', $data['value'])
                        )
                    )),
                SelectFilter::make('ao_ejecucion')
                    ->label('Año de ejecución')
                    ->options(
                        fn (): array => DatosEjecucionObras::query()
                            ->select('ao_ejecucion')
                            ->distinct()
                            ->whereNotNull('ao_ejecucion')
                            ->orderBy('ao_ejecucion', 'desc')
                            ->pluck('ao_ejecucion', 'ao_ejecucion')
                            ->toArray()
                    )
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        filled($data['value'] ?? null),
                        fn (Builder $query): Builder => $query->whereHas(
                            'ejecucion',
                            fn (Builder $query): Builder => $query->where('ao_ejecucion', $data['value'])
                        )
                    )),
                Filter::make('busqueda')
                    ->label('Búsqueda rápida')
                    ->schema([
                        TextInput::make('expediente_id')
                            ->label('Expediente'),
                        TextInput::make('numero_obra')
                            ->label('Obra'),
                        TextInput::make('subreferencia')
                            ->label('Subreferencia'),
                        TextInput::make('nombre_obra')
                            ->label('Nombre obra'),
                    ])
                    ->columns(4)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(filled($data['expediente_id'] ?? null), fn (Builder $query): Builder => $query->where('expediente_id', 'like', '%' . $data['expediente_id'] . '%'))
                            ->when(filled($data['numero_obra'] ?? null), fn (Builder $query): Builder => $query->whereHas('ejecucion', fn (Builder $query): Builder => $query->where('numero_obra', '=', $data['numero_obra'])))
                            ->when(filled($data['subreferencia'] ?? null), fn (Builder $query): Builder => $query->whereHas('ejecucion', fn (Builder $query): Builder => $query->where('subreferencia', '=', $data['subreferencia'])))
                            ->when(filled($data['nombre_obra'] ?? null), fn (Builder $query): Builder => $query->whereHas('ejecucion.obra', fn (Builder $query): Builder => $query->where('nombre_obra1', 'like', '%' . $data['nombre_obra'] . '%')));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActadereplanteos::route('/'),
            'create' => CreateActadereplanteo::route('/create'),
            'edit' => EditActadereplanteo::route('/{record}/edit'),
        ];
    }
}
