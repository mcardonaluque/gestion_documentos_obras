<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\PlanSsResource\Pages;
use App\Filament\Obras\Resources\PlanSsResource\RelationManagers;
use App\Models\Planseguridadysalud;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanSSResource extends Resource
{
    protected static ?string $model = Planseguridadysalud::class;
    protected static ?string $modelLabel = 'Plan de Seguridad';
    protected static ?string $pluralModelLabel = 'Planes de Seguridad'; 
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationGroup="Ejecución";
    protected static ?string $navigationLabel ='Planes de Seguridad y Salud';

    protected function getHeading(): string
    {
    return 'Título personalizado';
    }
    public static function getFormTitle(?string $operation = null): string 
{
    return match ($operation) {
        'create' => 'Crear Plan de Seguridad y Salud',
        'edit' => 'Editar Plan de Seguridad y Salud' . static::getModel()::find(request()->record)?->id,
        default => 'Plan de Seguridad y Salud',
    };
    
}
    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYear(2)->year;
        return parent::getEloquentQuery()
        ->select('PlanSeguridadYSalud.*') // Selecciona todas las columnas de la tabla "obras"
            //->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.Expediente', '=', 'Datos_Ejecucion_Obras.Expediente') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
            ->where('PlanSeguridadYSalud.ao_ejecucion', '>=', $añoAnterior2)
            ->where('PlanSeguridadYSalud.ao_ejecucion', '<=', $añoActual);
            //->where('codigo_municipio','=', )
      
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(7),
                Forms\Components\TextInput::make('Numero_obra')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('Subreferencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('FecPlanSyS'),
                Forms\Components\DateTimePicker::make('FecPeticionInfTec'),
                Forms\Components\DateTimePicker::make('FecRecepcionInfTec'),
                Forms\Components\DateTimePicker::make('FecDevolucionInfTec'),
                Forms\Components\DateTimePicker::make('FecPropuesta'),
                Forms\Components\TextInput::make('NumDecreto')
                    ->maxLength(10),
                Forms\Components\DateTimePicker::make('FecDecreto'),
                Forms\Components\DateTimePicker::make('FecComunicacionCont'),
                Forms\Components\DateTimePicker::make('FecComunicacionTrabajo'),
                Forms\Components\DateTimePicker::make('FecRecepAprobAyto'),
                Forms\Components\TextInput::make('Coordinador')
                    ->maxLength(100),
                Forms\Components\TextInput::make('observaciones')
                    ->maxLength(250),
                Forms\Components\DateTimePicker::make('FecSolicitudPSA'),
                Forms\Components\DateTimePicker::make('FecRequerimientoPSA'),
                Forms\Components\DateTimePicker::make('FecReclaAprob'),
                Forms\Components\DateTimePicker::make('FecRecepDev'),
                Forms\Components\DateTimePicker::make('FecRecInfJefe'),
                Forms\Components\DateTimePicker::make('FecReciboSol'),
                Forms\Components\DateTimePicker::make('FecReciboReq'),
                Forms\Components\DateTimePicker::make('FecPetInfCoor'),
                Forms\Components\DateTimePicker::make('FecDevInfCoor'),
                Forms\Components\DateTimePicker::make('FecDevPlanCoor'),
                Forms\Components\TextInput::make('FecDevPlanCoorLista')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('FecRecepPlanCoor'),
                Forms\Components\TextInput::make('FecRecepPlanCoorLista')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('FecRemCoor'),
                Forms\Components\DateTimePicker::make('FecRecepContrato'),
                Forms\Components\DateTimePicker::make('FecRecepAprobCoor'),
                Forms\Components\DateTimePicker::make('FecRemAprobCoor'),
                Forms\Components\DateTimePicker::make('FecRecepAvisoCoor'),
                Forms\Components\DateTimePicker::make('FecSolAprobAyto'),
                Forms\Components\DateTimePicker::make('FecEnvInfTecAyto'),
                Forms\Components\TextInput::make('CSVPSYS')
                    ->maxLength(50),
                Forms\Components\TextInput::make('CSVPGR')
                    ->maxLength(50),
                Forms\Components\TextInput::make('NumRegistroPSYS')
                    ->maxLength(50),
                Forms\Components\TextInput::make('NumRegistroPGR')
                    ->maxLength(50),
                Forms\Components\DateTimePicker::make('FecRecepPSYS'),
                Forms\Components\DateTimePicker::make('FecRecepPGR'),
                Forms\Components\TextInput::make('InfPSYS')
                    ->maxLength(50),
                Forms\Components\TextInput::make('InfPGR')
                    ->maxLength(50),
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
                Tables\Columns\TextColumn::make('Numero_obra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecPlanSyS')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecPeticionInfTec')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepcionInfTec')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecDevolucionInfTec')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecPropuesta')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('NumDecreto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('FecDecreto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecComunicacionCont')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecComunicacionTrabajo')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepAprobAyto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Coordinador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('observaciones')
                    ->searchable(),
                Tables\Columns\TextColumn::make('FecSolicitudPSA')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRequerimientoPSA')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecReclaAprob')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepDev')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecInfJefe')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecReciboSol')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecReciboReq')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecPetInfCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecDevInfCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecDevPlanCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecDevPlanCoorLista')
                    ->searchable(),
                Tables\Columns\TextColumn::make('FecRecepPlanCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepPlanCoorLista')
                    ->searchable(),
                Tables\Columns\TextColumn::make('FecRemCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepContrato')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepAprobCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRemAprobCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepAvisoCoor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecSolAprobAyto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecEnvInfTecAyto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CSVPSYS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CSVPGR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('NumRegistroPSYS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('NumRegistroPGR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('FecRecepPSYS')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FecRecepPGR')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('InfPSYS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('InfPGR')
                    ->searchable(),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanSses::route('/'),
            'create' => Pages\CreatePlanSs::route('/create'),
            'edit' => Pages\EditPlanSs::route('/{record}/edit'),
        ];  
    }
}
