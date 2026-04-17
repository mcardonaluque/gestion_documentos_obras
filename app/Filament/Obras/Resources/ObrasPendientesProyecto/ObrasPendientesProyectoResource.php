<?php

namespace App\Filament\Obras\Resources\ObrasPendientesProyecto;

use App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages;
use App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages\ListObrasPendientesProyectos;
use App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages\ViewObrasPendientesProyecto;
use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use App\Models\ObrasPendienteProyecto;
use App\Models\TablaDeDepartamento;
use App\Models\TablaDeMunicipio;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class ObrasPendientesProyectoResource extends Resource
{
    protected static ?string $model = ObrasPendienteProyecto::class;

    protected static ?string $slug = 'obras-pendientes-proyecto';
    protected static ?string $modelLabel = 'Obras Pendientes de Proyecto';
    protected static ?string $pluralModelLabel = 'Obras Pendientes de Proyecto';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'expediente_id';

      public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYears(10)->year;


        return parent::getEloquentQuery()
            ->select('V_ObrasPendientesProyecto.*')
            ->with(['municipios', 'servicioDir'])
            ->where('ao_ejecucion', '>=', $añoAnterior2)
            ->where('ao_ejecucion', '<=', $añoActual)
            ->where('Codigo_Plan', '<>', '');
            //->where('codigo_municipio','=', )

    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Infolists\Components\TextEntry::make('expediente_id'),
            ]);
    }

     public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expediente_id')->label('Expediente')->searchable(),
                Tables\Columns\TextColumn::make('Codigo_Plan')->label('Plan')->searchable(),
                Tables\Columns\TextColumn::make('numero_obra')->label('Obra')->searchable(),
                Tables\Columns\TextColumn::make('subreferencia')->label('Subref.')->searchable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')->label('Año Ejecución'),
                Tables\Columns\TextColumn::make('nombre_obra1')->label('Nombre Obra')->searchable(),
                Tables\Columns\TextColumn::make('servicioDir.DENOMINACION')->label('Serv. Dirección'),
                Tables\Columns\TextColumn::make('municipios.nombre_municipio')
                    ->label('Municipio')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('municipios', function (Builder $relationQuery) use ($search): void {
                            $relationQuery->where('nombre_municipio', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('importe_aprobado')->label('Importe Aprobado'),
            ])
            ->filters([
                Tables\Filters\Filter::make('expediente')
                    ->label('Expediente')
                    ->form([
                        TextInput::make('expediente_id')
                            ->label('Nº expediente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $expedienteId = trim((string) ($data['expediente_id'] ?? ''));

                        if ($expedienteId === '') {
                            return $query;
                        }

                        return $query->where('expediente_id', 'like', "%{$expedienteId}%");
                    }),
                Tables\Filters\SelectFilter::make('municipio')
                    ->label('Municipio')
                    ->options(TablaDeMunicipio::orderBy('nombre_municipio')->pluck('nombre_municipio', 'codigo_municipio'))
                    ->query(function (Builder $query, array $data): Builder {
                        $codigo = $data['value'] ?? null;

                        if ($codigo === null || $codigo === '') {
                            return $query;
                        }

                        return $query->where('municipio', $codigo);
                    }),
                Tables\Filters\SelectFilter::make('OrganismoRedactor')
                    ->options(\App\Models\organismo::pluck('denominacion', 'codigo_organismo')),

                Tables\Filters\SelectFilter::make('ServicioRedactor')
                    ->options(TablaDeDepartamento::pluck('DENOMINACION', 'CODIGO_DPTO')),

                Tables\Filters\SelectFilter::make('OrganismoDireccion')
                    ->options(\App\Models\organismo::pluck('denominacion', 'codigo_organismo')),

                Tables\Filters\SelectFilter::make('ServicioDireccion')
                    ->options(TablaDeDepartamento::pluck('DENOMINACION', 'CODIGO_DPTO')),
            ])
            ->recordActions([
                Action::make('addProyecto')
                    ->label('Añadir Proyecto')
                    ->icon('heroicon-o-plus')
                    ->url(fn ($record): string => ProyectoResource::getUrl('create', [
                        'expediente_id' => $record->expediente_id,
                        'codigo_municipio' => $record->codigo_municipio,
                        'ao_ejecucion' => $record->ao_ejecucion,
                        'codigo_plan' => $record->Codigo_Plan,
                        'numero_obra' => $record->numero_obra,
                        'subreferencia' => $record->subreferencia,
                    ])),
            ])
            ->defaultSort('expediente_id','desc');
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
            'index' => ListObrasPendientesProyectos::route('/'),
            'view' => ViewObrasPendientesProyecto::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
