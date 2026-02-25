<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
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
use App\Filament\Obras\Resources\DatosEjecucionObrasResource\Pages;
use App\Filament\Obras\Resources\DatosEjecucionObrasResource\RelationManagers;
use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\CertificacionesRelationManager;
use App\Models\DatosEjecucionObras;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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

        $añoAnterior2 = now()->year - 10;
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
                DateTimePicker::make('Fecha_Inicio_Acta_Replanteo'),
                DateTimePicker::make('Fecha_Final_Acta_Replanteo'),
                DateTimePicker::make('Fecha_Prorroga_Acta_Replanteo'),
                Toggle::make('Indicador_Impresion_AR')
                    ->required(),
                Toggle::make('Indicador_Recepcion_AR')
                    ->required(),
                TextInput::make('TipoActaRecepcion')
                    ->maxLength(1),
                DateTimePicker::make('Fecha_Acta_RecProv'),
                TextInput::make('Lugar_Acta_Rec')
                    ->maxLength(25),
                DateTimePicker::make('Fecha_Com_Inf'),
                DateTimePicker::make('Fecha_Edicto_BOE'),
                DateTimePicker::make('Fecha_BOE'),
                TextInput::make('Num_BOE')
                    ->maxLength(3),
                TextInput::make('Plazo_Reclam')
                    ->numeric(),
                DateTimePicker::make('Fecha_Certif_NO_Reclam'),
                DateTimePicker::make('Fecha_Com_Inf_2'),
                DateTimePicker::make('Fecha_Com_Gob'),
                DateTimePicker::make('Fecha_Comun_Contrat'),
                DateTimePicker::make('Fecha_Certif_Liquid'),
                DateTimePicker::make('Fecha_Rem_Interv'),
                DateTimePicker::make('Fecha_Rem_MAP'),
                TextInput::make('Admin_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Dir_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Alcalde_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Cont_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Interv_ActaRecepcion')
                    ->maxLength(60),
                TextInput::make('Dipu_ActaRecepcion')
                    ->maxLength(60),
                Textarea::make('Texto')
                    ->columnSpanFull(),
                DateTimePicker::make('Fecha_Paralizacion_Temporal'),
                TextInput::make('Motivo_Paralizacion')
                    ->maxLength(200),
                DateTimePicker::make('Fecha_Aprob_Paralizacion_Temporal'),
                DateTimePicker::make('Fecha_Inicio_Paralizacion'),
                DateTimePicker::make('Fecha_Final_Paralizacion'),
                DateTimePicker::make('Fecha_Acta_Rec'),
                DateTimePicker::make('Fecha_Aviso_Finalizacion'),
                DateTimePicker::make('Fecha_Aviso_FinalizacionMAP'),
                DateTimePicker::make('Fecha_Medicion'),
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
                TextColumn::make('Fecha_Inicio_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Final_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Prorroga_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('Indicador_Impresion_AR')
                    ->boolean(),
                IconColumn::make('Indicador_Recepcion_AR')
                    ->boolean(),
                TextColumn::make('TipoActaRecepcion')
                    ->searchable(),
                TextColumn::make('Fecha_Acta_RecProv')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Lugar_Acta_Rec')
                    ->searchable(),
                TextColumn::make('Fecha_Com_Inf')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Edicto_BOE')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_BOE')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Num_BOE')
                    ->searchable(),
                TextColumn::make('Plazo_Reclam')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Fecha_Certif_NO_Reclam')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Com_Inf_2')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Com_Gob')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Comun_Contrat')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Certif_Liquid')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Rem_Interv')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Rem_MAP')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Admin_ActaRecepcion')
                    ->searchable(),
                TextColumn::make('Dir_ActaRecepcion')
                    ->searchable(),
                TextColumn::make('Alcalde_ActaRecepcion')
                    ->searchable(),
                TextColumn::make('Cont_ActaRecepcion')
                    ->searchable(),
                TextColumn::make('Interv_ActaRecepcion')
                    ->searchable(),
                TextColumn::make('Dipu_ActaRecepcion')
                    ->searchable(),
                TextColumn::make('Fecha_Paralizacion_Temporal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Motivo_Paralizacion')
                    ->searchable(),
                TextColumn::make('Fecha_Aprob_Paralizacion_Temporal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Inicio_Paralizacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Final_Paralizacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Acta_Rec')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Aviso_Finalizacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Aviso_FinalizacionMAP')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Medicion')
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
