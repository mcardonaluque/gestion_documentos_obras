<?php

namespace App\Filament\Obras\Resources\Proyectos;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\Proyectos\Pages\ListProyectos;
use App\Filament\Obras\Resources\Proyectos\Pages\CreateProyecto;
use App\Filament\Obras\Resources\Proyectos\Pages\EditProyecto;
use App\Filament\Obras\Resources\ProyectoResource\Pages;
use App\Filament\Obras\Resources\ProyectoResource\RelationManagers;
use App\Filament\Obras\Resources\Proyectos\RelationManagers\FasesRelationManager;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $modelLabel = 'Proyecto';
    protected static ?string $pluralModelLabel = 'Proyectos';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?int $navigationSort = 2;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static \UnitEnum|string|null $navigationGroup="Proyectos";
    protected static ?string $navigationLabel ='Proyectos';**/

    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->year - 10;
        return parent::getEloquentQuery()
        ->select('Proyectos.*') // Selecciona todas las columnas de la tabla "obras"
            //->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.Expediente', '=', 'Datos_Ejecucion_Obras.Expediente') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
           ->where('Proyectos.Expediente', '!=', NULL)
           ->where('Proyectos.AO_PROYECTO', '>=', $añoAnterior2)
           ->where('Proyectos.AO_PROYECTO', '<=', $añoActual);
            //->where('codigo_municipio','=', )

    }
    public static function form(Schema $schema): Schema
    {
        return $schema
        ->columns(7)
            ->components([
                TextInput::make('CODIGO_MUNICIPIO')
                    ->required()
                    ->numeric(),
                TextInput::make('AO_PROYECTO')
                    ->required()
                    ->searchable()
                    ->numeric(),
                TextInput::make('NUMERO_PROYECTO')
                    ->required()
                    ->numeric(),
                TextInput::make('Servicio_Gestor')
                    ->numeric(),
                Toggle::make('Compartido')
                    ->required(),
                TextInput::make('den_proyecto')
                    ->searchable()
                    ->maxLength(1000),
                TextInput::make('importe_proyecto_Pts')
                    ->numeric(),
                TextInput::make('organismo_redactor')
                    ->maxLength(4),
                TextInput::make('Servicio_redactor')
                    ->numeric(),
                TextInput::make('autor')
                    ->maxLength(100),
                TextInput::make('ColegioOficial')
                    ->maxLength(2),
                Toggle::make('SubvencionEconRedaccion')
                    ->required(),
                TextInput::make('carretera')
                    ->maxLength(30),
                TextInput::make('plazo')
                    ->numeric(),
                TextInput::make('UnidadPlazo')
                    ->maxLength(1)
                    ->default('m'),
                TextInput::make('nro_ejemplares')
                    ->numeric(),
                TextInput::make('grupo')
                    ->maxLength(2),
                TextInput::make('subgrupo')
                    ->maxLength(18),
                TextInput::make('categoria')
                    ->maxLength(2),
                TextInput::make('revision')
                    ->maxLength(2),
                TextInput::make('formula')
                    ->numeric(),
                TextInput::make('formula2')
                    ->numeric(),
                TextInput::make('formula3')
                    ->numeric(),
                TextInput::make('formula4')
                    ->numeric(),
                TextInput::make('presu_gral_ejecucion_material_Pts')
                    ->numeric(),
                TextInput::make('por_gastos_generales')
                    ->numeric(),
                TextInput::make('importe_gastos_generales_Pts')
                    ->numeric(),
                TextInput::make('por_beneficio_industriales')
                    ->numeric(),
                TextInput::make('importe_beneficio_industriales_Pts')
                    ->numeric(),
                TextInput::make('por_control_calidad')
                    ->numeric(),
                TextInput::make('importe_control_calidad_Pts')
                    ->numeric(),
                TextInput::make('por_iva')
                    ->numeric(),
                TextInput::make('iva_Pts')
                    ->numeric(),
                TextInput::make('por_subcontrata')
                    ->numeric(),
                TextInput::make('subcontrata_Pts')
                    ->numeric(),
                TextInput::make('honorarios_dir_Pts')
                    ->numeric(),
                TextInput::make('honorarios_red_Pts')
                    ->numeric(),
                DateTimePicker::make('fecha_entrega_proyecto'),
                DateTimePicker::make('fecha_recepcion_proyecto'),
                DateTimePicker::make('fecha_remision_ayto'),
                DateTimePicker::make('fecha_aprobacion_ayto'),
                DateTimePicker::make('fecha_pet_rectificacion'),
                DateTimePicker::make('fecha_ent_rectificacion'),
                DateTimePicker::make('fecha_pet_reforma'),
                DateTimePicker::make('fecha_ent_reforma'),
                DateTimePicker::make('fecha_c_infor'),
                DateTimePicker::make('fecha_c_gob'),
                TextInput::make('Punto')
                    ->maxLength(10),
                DateTimePicker::make('fecha_pit_ref'),
                DateTimePicker::make('fecha_eit_ref'),
                DateTimePicker::make('fecha_ci_ref'),
                DateTimePicker::make('fecha_cg_ref'),
                DateTimePicker::make('fecha_dto'),
                TextInput::make('nro_dto')
                    ->numeric(),
                TextInput::make('observaciones')
                    ->maxLength(1000),
                TextInput::make('estado_proyecto')
                    ->maxLength(50),
                Toggle::make('Requiere_PlanSyS')
                    ->required(),
                TextInput::make('importe_proyecto')
                    ->numeric(),
                TextInput::make('presu_gral_ejecucion_material')
                    ->numeric(),
                TextInput::make('importe_gastos_generales')
                    ->numeric(),
                TextInput::make('importe_beneficio_industriales')
                    ->numeric(),
                TextInput::make('importe_control_calidad')
                    ->numeric(),
                TextInput::make('iva')
                    ->numeric(),
                TextInput::make('subcontrata')
                    ->numeric(),
                TextInput::make('honorarios_dir')
                    ->numeric(),
                Toggle::make('HD_ExcluidoIVA')
                    ->required(),
                TextInput::make('honorarios_red')
                    ->numeric(),
                Toggle::make('HR_ExcluidoIVA')
                    ->required(),
                TextInput::make('importePlanSyS')
                    ->numeric(),
                Toggle::make('Requiere_TramAmbiental')
                    ->required(),
                Select::make('team_id')
                    ->relationship('team', 'name'),
                TextInput::make('Codigo_Plan')
                    ->maxLength(14),
                TextInput::make('referencia')
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->numeric(),
                TextInput::make('organismo_direccion')
                    ->maxLength(4),
                TextInput::make('director_tecnico')
                    ->numeric(),
                TextInput::make('servicio_direccion')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('municipio.nombre_municipio')
                    ->numeric()
                    ->label('Municipio')
                    ->sortable(),
                TextColumn::make('AO_PROYECTO')
                    ->label('Año')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('NUMERO_PROYECTO')
                ->label('Número')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('servicioGestor.DENOMINACION')

                    ->numeric()
                    ->sortable(),
                IconColumn::make('Compartido')
                    ->boolean(),
                TextColumn::make('den_proyecto')
                    ->searchable(),
                //Tables\Columns\TextColumn::make('importe_proyecto_Pts')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('organismo_redactor')
                    ->searchable(),
                TextColumn::make('servicioRed.DENOMINACION')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('autor')
                    ->searchable(),
                TextColumn::make('ColegioOficial')
                    ->searchable(),
                IconColumn::make('SubvencionEconRedaccion')
                    ->boolean(),
                TextColumn::make('carretera')
                    ->searchable(),
                TextColumn::make('plazo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('UnidadPlazo')
                    ->searchable(),
                TextColumn::make('nro_ejemplares')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('grupo')
                    ->searchable(),
                TextColumn::make('subgrupo')
                    ->searchable(),
                TextColumn::make('categoria')
                    ->searchable(),
                TextColumn::make('revision')
                    ->searchable(),
                TextColumn::make('formula')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('formula2')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('formula3')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('formula4')
                    ->numeric()
                    ->sortable(),
               // Tables\Columns\TextColumn::make('presu_gral_ejecucion_material_Pts')
               //     ->numeric()
               //     ->sortable(),
                TextColumn::make('por_gastos_generales')
                    ->numeric()
                    ->sortable(),
               // Tables\Columns\TextColumn::make('importe_gastos_generales_Pts')
               //     ->numeric()
               //     ->sortable(),
                TextColumn::make('por_beneficio_industriales')
                    ->numeric()
                    ->sortable(),
                //Tables\Columns\TextColumn::make('importe_beneficio_industriales_Pts')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('por_control_calidad')
                    ->numeric()
                    ->sortable(),
               // Tables\Columns\TextColumn::make('importe_control_calidad_Pts')
               //     ->numeric()
               //     ->sortable(),
                TextColumn::make('por_iva')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('iva_Pts')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('por_subcontrata')
                    ->numeric()
                    ->sortable(),
                //Tables\Columns\TextColumn::make('subcontrata_Pts')
                //    ->numeric()
                //    ->sortable(),
                //Tables\Columns\TextColumn::make('honorarios_dir_Pts')
                //    ->numeric()
                //    ->sortable(),
                //Tables\Columns\TextColumn::make('honorarios_red_Pts')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('fecha_entrega_proyecto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_recepcion_proyecto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_remision_ayto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_aprobacion_ayto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_pet_rectificacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_ent_rectificacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_pet_reforma')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_ent_reforma')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_c_infor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_c_gob')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Punto')
                    ->searchable(),
                TextColumn::make('fecha_pit_ref')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_eit_ref')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_ci_ref')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_cg_ref')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_dto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('nro_dto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('observaciones')
                    ->searchable(),
                TextColumn::make('estado_proyecto')
                    ->searchable(),
                IconColumn::make('Requiere_PlanSyS')
                    ->boolean(),
                TextColumn::make('importe_proyecto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('presu_gral_ejecucion_material')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('importe_gastos_generales')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('importe_beneficio_industriales')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('importe_control_calidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('iva')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subcontrata')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('honorarios_dir')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('HD_ExcluidoIVA')
                    ->boolean(),
                TextColumn::make('honorarios_red')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('HR_ExcluidoIVA')
                    ->boolean(),
                TextColumn::make('importePlanSyS')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('Requiere_TramAmbiental')
                    ->boolean(),
                TextColumn::make('Expediente')
                    ->searchable(),
               // Tables\Columns\TextColumn::make('team.name')
               //     ->numeric()
               //     ->sortable(),
               // Tables\Columns\TextColumn::make('created_at')
               //     ->dateTime()
               //     ->sortable()
               //     ->toggleable(isToggledHiddenByDefault: true),
               // Tables\Columns\TextColumn::make('updated_at')
               //     ->dateTime()
               //     ->sortable()
               //     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('Codigo_Plan')
                    ->searchable(),
                TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('organismo_direccion')
                    ->searchable(),
                TextColumn::make('director_tecnico')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('servicioDir.DENOMINACION')
                    ->numeric()
                    ->sortable(),
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
            FasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProyectos::route('/'),
            'create' => CreateProyecto::route('/create'),
            'edit' => EditProyecto::route('/{record}/edit'),
        ];
    }
}
