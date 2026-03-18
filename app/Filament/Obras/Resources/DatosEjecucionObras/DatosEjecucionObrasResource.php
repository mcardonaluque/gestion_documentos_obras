<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\DatosEjecucionObras\Pages\ListDatosEjecucionObras;
use App\Filament\Obras\Resources\DatosEjecucionObras\Pages\CreateDatosEjecucionObras;
use App\Filament\Obras\Resources\DatosEjecucionObras\Pages\EditDatosEjecucionObras;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\ImportesPorOrganismoRelationManager;

use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\ActaRecepcionRelationManager;
use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\ActaReplanteoRelationManager;
use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\CertificacionesRelationManager;
use App\Models\DatosEjecucionObras;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Forms\Components\ObraGeneralInfo;
use App\Filament\Traits\CommonFilters;
use App\Filament\Traits\MunicipiosFilter;
class DatosEjecucionObrasResource extends Resource
{
    use MunicipiosFilter;
    protected static ?string $model = DatosEjecucionObras::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?int $navigationSort = 3;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static \UnitEnum|string|null $navigationGroup="Ejecución";
    protected static ?string $navigationLabel ='Ejecución de Obras';
   **/
    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYears(10)->year;
        return parent::getEloquentQuery()
        ->select('Datos_Ejecucion_Obras.*') // Selecciona todas las columnas de la tabla "obras"
            //->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.Expediente', '=', 'Datos_Ejecucion_Obras.Expediente') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
            ->where('Datos_Ejecucion_Obras.ao_ejecucion', '>=', $añoAnterior2)
            ->where('Datos_Ejecucion_Obras.ao_ejecucion', '<=', $añoActual);
            //->where('codigo_municipio','=', )

    }
    public static function form(Schema $schema): Schema
    {
        $record=$schema->getRecord();
        return $schema
        ->columns(7)
            ->components([
                ObraGeneralInfo::make('informacion_general')
                ->label('Información General de la Obra')
                ->SetObraData($record ?? null),
                TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(7),
                TextInput::make('numero_obra')
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
              TextInput::make('expediente_id')
                   // ->searchable()
                    ->disabled(),
                Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }
    use CommonFilters;
    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('Codigo_Plan')
                    ->searchable(),
                TextColumn::make('numero_obra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('obra.nombre_obra1')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('actaReplanteo.Fecha_Inicio_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('actaReplanteo.Fecha_Final_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('actaReplanteo.Fecha_Prorroga_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('actaReplanteo.Indicador_Impresion_AR')
                    ->boolean(),
                IconColumn::make('actaReplanteo.Indicador_Recepcion_AR')
                    ->boolean(),
                TextColumn::make('actaRecepcion.TipoActaRecepcion')
                    ->searchable(),
                TextColumn::make('actaRecepcion.Fecha_Acta_RecProv')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('actaRecepcion.Lugar_Acta_Rec')
                    ->searchable(),
                TextColumn::make('actaRecepcion.Fecha_Acta_Rec')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                ...self::getCommonFilters(),
                self::getMunicipioFromInicioObrasFilter(),
            ],layout: FiltersLayout::AboveContent)

            ->headerActions([
                CreateAction::make(),
            ])
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
            CertificacionesRelationManager::class,
            ImportesPorOrganismoRelationManager::class,
            ActaReplanteoRelationManager::class,
            ActaRecepcionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDatosEjecucionObras::route('/'),
            'create' => CreateDatosEjecucionObras::route('/create'),
            'edit' => EditDatosEjecucionObras::route('/{record}/edit'),
        ];
    }
}
