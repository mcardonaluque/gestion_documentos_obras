<?php

namespace App\Filament\Obras\Resources\ObrasPendientesProyecto;

use App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages;
use App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages\ListObrasPendientesProyectos;
use App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages\ViewObrasPendientesProyecto;
use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use App\Models\ObrasPendienteProyecto;
use App\Models\TablaDeDepartamento;
use BackedEnum;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'expediente_id';

      public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYears(10)->year;


        return parent::getEloquentQuery()
        ->select('DatosInicioDeObras.*') // Selecciona todas las columnas de la tabla "obras"
            ->leftJoin('TablaDeMunicipios', 'DatosInicioDeObras.municipio', '=', 'codigo_municipio') // Join con la tabla "municipios"
            ->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
            ->where('ao_ejecucion', '>=', $añoAnterior2)
            ->where('ao_ejecucion', '<=', $añoActual)
            ->where('Codigo_Plan','<>','');
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
                Tables\Columns\TextColumn::make('expediente_id')->label('Expediente'),
                Tables\Columns\TextColumn::make('Codigo_Plan')->label('Plan'),
                Tables\Columns\TextColumn::make('numero_obra')->label('Obra'),
                Tables\Columns\TextColumn::make('subreferencia')->label('Subref.'),
                Tables\Columns\TextColumn::make('ao_ejecucion')->label('Año Ejecución'),
                Tables\Columns\TextColumn::make('nombre_obra1')->label('Nombre Obra'),
                Tables\Columns\TextColumn::make('servicioDir.DENOMINACION')->label('Serv. Dirección'),
                Tables\Columns\TextColumn::make('municipios.nombre_municipio')->label('Municipio'),
                Tables\Columns\TextColumn::make('importe_aprobado')->label('Importe Aprobado'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('OrganismoRedactor')
                    ->options(\App\Models\organismo::pluck('denominacion', 'codigo_organismo')),

                Tables\Filters\SelectFilter::make('ServicioRedactor')
                    ->options(TablaDeDepartamento::pluck('DENOMINACION', 'CODIGO_DPTO')),

                Tables\Filters\SelectFilter::make('OrganismoDireccion')
                    ->options(\App\Models\organismo::pluck('denominacion', 'codigo_organismo')),

                Tables\Filters\SelectFilter::make('ServicioDireccion')
                    ->options(TablaDeDepartamento::pluck('DENOMINACION', 'CODIGO_DPTO')),
            ])
            ->recordUrl(fn ($record) => ProyectoResource::getUrl('create', [
                'expediente_id' => $record->expediente_id,
            ]))
            ->recordAction(null)
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
