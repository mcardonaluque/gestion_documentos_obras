<?php

namespace App\Filament\Obras\Resources\PlanSses;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\PlanSses\Pages\ListPlanSses;
use App\Filament\Obras\Resources\PlanSses\Pages\CreatePlanSs;
use App\Filament\Obras\Resources\PlanSses\Pages\EditPlanSs;

use App\Models\Planseguridadysalud;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlanSsResource extends Resource
{
    protected static ?string $model = Planseguridadysalud::class;
    protected static ?string $modelLabel = 'Plan de Seguridad';
    protected static ?string $pluralModelLabel = 'Planes de Seguridad';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    //protected static ?string $navigationGroup="Ejecución";**/
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

        $añoAnterior2 = now()->subYears(2)->year;
        return parent::getEloquentQuery()
        ->select('PlanSeguridadYSalud.*') // Selecciona todas las columnas de la tabla "obras"
            //->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.Expediente', '=', 'Datos_Ejecucion_Obras.Expediente') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
            ->where('PlanSeguridadYSalud.ao_ejecucion', '>=', $añoAnterior2)
            ->where('PlanSeguridadYSalud.ao_ejecucion', '<=', $añoActual);
            //->where('codigo_municipio','=', )

    }
    public static function form(Schema $schema): Schema
    {
        return $schema
        ->columns(7)
            ->components([
                TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(7),
                TextInput::make('Numero_obra')
                    ->required()
                    ->numeric(),
                TextInput::make('Subreferencia')
                    ->required()
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('FecPlanSyS'),
                DateTimePicker::make('FecPeticionInfTec'),
                DateTimePicker::make('FecRecepcionInfTec'),
                DateTimePicker::make('FecDevolucionInfTec'),
                DateTimePicker::make('FecPropuesta'),
                TextInput::make('NumDecreto')
                    ->maxLength(10),
                DateTimePicker::make('FecDecreto'),
                DateTimePicker::make('FecComunicacionCont'),
                DateTimePicker::make('FecComunicacionTrabajo'),
                DateTimePicker::make('FecRecepAprobAyto'),
                TextInput::make('Coordinador')
                    ->maxLength(100),
                TextInput::make('observaciones')
                    ->maxLength(250),
                DateTimePicker::make('FecSolicitudPSA'),
                DateTimePicker::make('FecRequerimientoPSA'),
                DateTimePicker::make('FecReclaAprob'),
                DateTimePicker::make('FecRecepDev'),
                DateTimePicker::make('FecRecInfJefe'),
                DateTimePicker::make('FecReciboSol'),
                DateTimePicker::make('FecReciboReq'),
                DateTimePicker::make('FecPetInfCoor'),
                DateTimePicker::make('FecDevInfCoor'),
                DateTimePicker::make('FecDevPlanCoor'),
                TextInput::make('FecDevPlanCoorLista')
                    ->maxLength(255),
                DateTimePicker::make('FecRecepPlanCoor'),
                TextInput::make('FecRecepPlanCoorLista')
                    ->maxLength(255),
                DateTimePicker::make('FecRemCoor'),
                DateTimePicker::make('FecRecepContrato'),
                DateTimePicker::make('FecRecepAprobCoor'),
                DateTimePicker::make('FecRemAprobCoor'),
                DateTimePicker::make('FecRecepAvisoCoor'),
                DateTimePicker::make('FecSolAprobAyto'),
                DateTimePicker::make('FecEnvInfTecAyto'),
                TextInput::make('CSVPSYS')
                    ->maxLength(50),
                TextInput::make('CSVPGR')
                    ->maxLength(50),
                TextInput::make('NumRegistroPSYS')
                    ->maxLength(50),
                TextInput::make('NumRegistroPGR')
                    ->maxLength(50),
                DateTimePicker::make('FecRecepPSYS'),
                DateTimePicker::make('FecRecepPGR'),
                TextInput::make('InfPSYS')
                    ->maxLength(50),
                TextInput::make('InfPGR')
                    ->maxLength(50),
                Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Codigo_Plan')
                    ->searchable(),
                TextColumn::make('Numero_obra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('FecPlanSyS')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecPeticionInfTec')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepcionInfTec')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecDevolucionInfTec')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecPropuesta')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('NumDecreto')
                    ->searchable(),
                TextColumn::make('FecDecreto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecComunicacionCont')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecComunicacionTrabajo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepAprobAyto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Coordinador')
                    ->searchable(),
                TextColumn::make('observaciones')
                    ->searchable(),
                TextColumn::make('FecSolicitudPSA')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRequerimientoPSA')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecReclaAprob')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepDev')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecInfJefe')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecReciboSol')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecReciboReq')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecPetInfCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecDevInfCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecDevPlanCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecDevPlanCoorLista')
                    ->searchable(),
                TextColumn::make('FecRecepPlanCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepPlanCoorLista')
                    ->searchable(),
                TextColumn::make('FecRemCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepContrato')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepAprobCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRemAprobCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepAvisoCoor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecSolAprobAyto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecEnvInfTecAyto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('CSVPSYS')
                    ->searchable(),
                TextColumn::make('CSVPGR')
                    ->searchable(),
                TextColumn::make('NumRegistroPSYS')
                    ->searchable(),
                TextColumn::make('NumRegistroPGR')
                    ->searchable(),
                TextColumn::make('FecRecepPSYS')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FecRecepPGR')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('InfPSYS')
                    ->searchable(),
                TextColumn::make('InfPGR')
                    ->searchable(),
                TextColumn::make('Expediente')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlanSses::route('/'),
            'create' => CreatePlanSs::route('/create'),
            'edit' => EditPlanSs::route('/{record}/edit'),
        ];
    }
}
