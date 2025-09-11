<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\ImportesPorOrganismoRelationManager;
use App\Filament\Obras\Resources\DatosEjecucionObrasResource\Pages;
use App\Filament\Obras\Resources\DatosEjecucionObrasResource\RelationManagers;
use App\Filament\Obras\Resources\DatosEjecucionObrasResource\RelationManagers\CertificacionesRelationManager;
use App\Models\DatosEjecucionObras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DatosEjecucionObrasResource extends Resource
{
    protected static ?string $model = DatosEjecucionObras::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationGroup="Ejecución";
    protected static ?string $navigationLabel ='Ejecución de Obras';
   
    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYear(2)->year;
        return parent::getEloquentQuery()
        ->select('Datos_Ejecucion_Obras.*') // Selecciona todas las columnas de la tabla "obras"
            //->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.Expediente', '=', 'Datos_Ejecucion_Obras.Expediente') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
            ->where('Datos_Ejecucion_Obras.ao_ejecucion', '>=', $añoAnterior2)
            ->where('Datos_Ejecucion_Obras.ao_ejecucion', '<=', $añoActual);
            //->where('codigo_municipio','=', )
      
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(7),
                Forms\Components\TextInput::make('numero_obra')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('Fecha_Inicio_Acta_Replanteo'),
                Forms\Components\DateTimePicker::make('Fecha_Final_Acta_Replanteo'),
                Forms\Components\DateTimePicker::make('Fecha_Prorroga_Acta_Replanteo'),
                Forms\Components\Toggle::make('Indicador_Impresion_AR')
                    ->required(),
                Forms\Components\Toggle::make('Indicador_Recepcion_AR')
                    ->required(),
                Forms\Components\TextInput::make('TipoActaRecepcion')
                    ->maxLength(1),
                Forms\Components\DateTimePicker::make('Fecha_Acta_RecProv'),
                Forms\Components\TextInput::make('Lugar_Acta_Rec')
                    ->maxLength(25),
                Forms\Components\DateTimePicker::make('Fecha_Com_Inf'),
                Forms\Components\DateTimePicker::make('Fecha_Edicto_BOE'),
                Forms\Components\DateTimePicker::make('Fecha_BOE'),
                Forms\Components\TextInput::make('Num_BOE')
                    ->maxLength(3),
                Forms\Components\TextInput::make('Plazo_Reclam')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('Fecha_Certif_NO_Reclam'),
                Forms\Components\DateTimePicker::make('Fecha_Com_Inf_2'),
                Forms\Components\DateTimePicker::make('Fecha_Com_Gob'),
                Forms\Components\DateTimePicker::make('Fecha_Comun_Contrat'),
                Forms\Components\DateTimePicker::make('Fecha_Certif_Liquid'),
                Forms\Components\DateTimePicker::make('Fecha_Rem_Interv'),
                Forms\Components\DateTimePicker::make('Fecha_Rem_MAP'),
                Forms\Components\TextInput::make('Admin_ActaRecepcion')
                    ->maxLength(60),
                Forms\Components\TextInput::make('Dir_ActaRecepcion')
                    ->maxLength(60),
                Forms\Components\TextInput::make('Alcalde_ActaRecepcion')
                    ->maxLength(60),
                Forms\Components\TextInput::make('Cont_ActaRecepcion')
                    ->maxLength(60),
                Forms\Components\TextInput::make('Interv_ActaRecepcion')
                    ->maxLength(60),
                Forms\Components\TextInput::make('Dipu_ActaRecepcion')
                    ->maxLength(60),
                Forms\Components\Textarea::make('Texto')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('Fecha_Paralizacion_Temporal'),
                Forms\Components\TextInput::make('Motivo_Paralizacion')
                    ->maxLength(200),
                Forms\Components\DateTimePicker::make('Fecha_Aprob_Paralizacion_Temporal'),
                Forms\Components\DateTimePicker::make('Fecha_Inicio_Paralizacion'),
                Forms\Components\DateTimePicker::make('Fecha_Final_Paralizacion'),
                Forms\Components\DateTimePicker::make('Fecha_Acta_Rec'),
                Forms\Components\DateTimePicker::make('Fecha_Aviso_Finalizacion'),
                Forms\Components\DateTimePicker::make('Fecha_Aviso_FinalizacionMAP'),
                Forms\Components\DateTimePicker::make('Fecha_Medicion'),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Codigo_Plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_obra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Inicio_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Final_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Prorroga_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('Indicador_Impresion_AR')
                    ->boolean(),
                Tables\Columns\IconColumn::make('Indicador_Recepcion_AR')
                    ->boolean(),
                Tables\Columns\TextColumn::make('TipoActaRecepcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fecha_Acta_RecProv')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Lugar_Acta_Rec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fecha_Com_Inf')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Edicto_BOE')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_BOE')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Num_BOE')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Plazo_Reclam')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Certif_NO_Reclam')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Com_Inf_2')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Com_Gob')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Comun_Contrat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Certif_Liquid')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Rem_Interv')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Rem_MAP')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Admin_ActaRecepcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Dir_ActaRecepcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Alcalde_ActaRecepcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Cont_ActaRecepcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Interv_ActaRecepcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Dipu_ActaRecepcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fecha_Paralizacion_Temporal')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Motivo_Paralizacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fecha_Aprob_Paralizacion_Temporal')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Inicio_Paralizacion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Final_Paralizacion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Acta_Rec')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Aviso_Finalizacion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Aviso_FinalizacionMAP')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Medicion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Expediente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDatosEjecucionObras::route('/'),
            'create' => Pages\CreateDatosEjecucionObras::route('/create'),
            'edit' => Pages\EditDatosEjecucionObras::route('/{record}/edit'),
        ];
    }
}
