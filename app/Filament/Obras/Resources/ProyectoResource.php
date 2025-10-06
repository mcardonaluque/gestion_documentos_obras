<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\ProyectoResource\Pages;
use App\Filament\Obras\Resources\ProyectoResource\RelationManagers;
use App\Filament\Obras\Resources\ProyectoResource\RelationManagers\FasesRelationManager;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
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
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationGroup="Proyectos";
    protected static ?string $navigationLabel ='Proyectos';**/

    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYear(10)->year;
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
    public static function form(Form $form): Form
    {
        return $form
        ->columns(7)
            ->schema([
                Forms\Components\TextInput::make('CODIGO_MUNICIPIO')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('AO_PROYECTO')
                    ->required()
                    ->searchable()
                    ->numeric(),
                Forms\Components\TextInput::make('NUMERO_PROYECTO')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('Servicio_Gestor')
                    ->numeric(),
                Forms\Components\Toggle::make('Compartido')
                    ->required(),
                Forms\Components\TextInput::make('den_proyecto')
                    ->searchable()
                    ->maxLength(1000),
                Forms\Components\TextInput::make('importe_proyecto_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('organismo_redactor')
                    ->maxLength(4),
                Forms\Components\TextInput::make('Servicio_redactor')
                    ->numeric(),
                Forms\Components\TextInput::make('autor')
                    ->maxLength(100),
                Forms\Components\TextInput::make('ColegioOficial')
                    ->maxLength(2),
                Forms\Components\Toggle::make('SubvencionEconRedaccion')
                    ->required(),
                Forms\Components\TextInput::make('carretera')
                    ->maxLength(30),
                Forms\Components\TextInput::make('plazo')
                    ->numeric(),
                Forms\Components\TextInput::make('UnidadPlazo')
                    ->maxLength(1)
                    ->default('m'),
                Forms\Components\TextInput::make('nro_ejemplares')
                    ->numeric(),
                Forms\Components\TextInput::make('grupo')
                    ->maxLength(2),
                Forms\Components\TextInput::make('subgrupo')
                    ->maxLength(18),
                Forms\Components\TextInput::make('categoria')
                    ->maxLength(2),
                Forms\Components\TextInput::make('revision')
                    ->maxLength(2),
                Forms\Components\TextInput::make('formula')
                    ->numeric(),
                Forms\Components\TextInput::make('formula2')
                    ->numeric(),
                Forms\Components\TextInput::make('formula3')
                    ->numeric(),
                Forms\Components\TextInput::make('formula4')
                    ->numeric(),
                Forms\Components\TextInput::make('presu_gral_ejecucion_material_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('por_gastos_generales')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_gastos_generales_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('por_beneficio_industriales')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_beneficio_industriales_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('por_control_calidad')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_control_calidad_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('por_iva')
                    ->numeric(),
                Forms\Components\TextInput::make('iva_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('por_subcontrata')
                    ->numeric(),
                Forms\Components\TextInput::make('subcontrata_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('honorarios_dir_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('honorarios_red_Pts')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('fecha_entrega_proyecto'),
                Forms\Components\DateTimePicker::make('fecha_recepcion_proyecto'),
                Forms\Components\DateTimePicker::make('fecha_remision_ayto'),
                Forms\Components\DateTimePicker::make('fecha_aprobacion_ayto'),
                Forms\Components\DateTimePicker::make('fecha_pet_rectificacion'),
                Forms\Components\DateTimePicker::make('fecha_ent_rectificacion'),
                Forms\Components\DateTimePicker::make('fecha_pet_reforma'),
                Forms\Components\DateTimePicker::make('fecha_ent_reforma'),
                Forms\Components\DateTimePicker::make('fecha_c_infor'),
                Forms\Components\DateTimePicker::make('fecha_c_gob'),
                Forms\Components\TextInput::make('Punto')
                    ->maxLength(10),
                Forms\Components\DateTimePicker::make('fecha_pit_ref'),
                Forms\Components\DateTimePicker::make('fecha_eit_ref'),
                Forms\Components\DateTimePicker::make('fecha_ci_ref'),
                Forms\Components\DateTimePicker::make('fecha_cg_ref'),
                Forms\Components\DateTimePicker::make('fecha_dto'),
                Forms\Components\TextInput::make('nro_dto')
                    ->numeric(),
                Forms\Components\TextInput::make('observaciones')
                    ->maxLength(1000),
                Forms\Components\TextInput::make('estado_proyecto')
                    ->maxLength(50),
                Forms\Components\Toggle::make('Requiere_PlanSyS')
                    ->required(),
                Forms\Components\TextInput::make('importe_proyecto')
                    ->numeric(),
                Forms\Components\TextInput::make('presu_gral_ejecucion_material')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_gastos_generales')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_beneficio_industriales')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_control_calidad')
                    ->numeric(),
                Forms\Components\TextInput::make('iva')
                    ->numeric(),
                Forms\Components\TextInput::make('subcontrata')
                    ->numeric(),
                Forms\Components\TextInput::make('honorarios_dir')
                    ->numeric(),
                Forms\Components\Toggle::make('HD_ExcluidoIVA')
                    ->required(),
                Forms\Components\TextInput::make('honorarios_red')
                    ->numeric(),
                Forms\Components\Toggle::make('HR_ExcluidoIVA')
                    ->required(),
                Forms\Components\TextInput::make('importePlanSyS')
                    ->numeric(),
                Forms\Components\Toggle::make('Requiere_TramAmbiental')
                    ->required(),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
                Forms\Components\TextInput::make('Codigo_Plan')
                    ->maxLength(14),
                Forms\Components\TextInput::make('referencia')
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->numeric(),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->numeric(),
                Forms\Components\TextInput::make('organismo_direccion')
                    ->maxLength(4),
                Forms\Components\TextInput::make('director_tecnico')
                    ->numeric(),
                Forms\Components\TextInput::make('servicio_direccion')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('municipio.nombre_municipio')
                    ->numeric()
                    ->label('Municipio')
                    ->sortable(),
                Tables\Columns\TextColumn::make('AO_PROYECTO')
                    ->label('Año')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('NUMERO_PROYECTO')
                ->label('Número')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicioGestor.DENOMINACION')
                    
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('Compartido')
                    ->boolean(),
                Tables\Columns\TextColumn::make('den_proyecto')
                    ->searchable(),
                //Tables\Columns\TextColumn::make('importe_proyecto_Pts')
                //    ->numeric()
                //    ->sortable(),
                Tables\Columns\TextColumn::make('organismo_redactor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('servicioRed.DENOMINACION')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('autor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ColegioOficial')
                    ->searchable(),
                Tables\Columns\IconColumn::make('SubvencionEconRedaccion')
                    ->boolean(),
                Tables\Columns\TextColumn::make('carretera')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plazo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('UnidadPlazo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_ejemplares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grupo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subgrupo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoria')
                    ->searchable(),
                Tables\Columns\TextColumn::make('revision')
                    ->searchable(),
                Tables\Columns\TextColumn::make('formula')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('formula2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('formula3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('formula4')
                    ->numeric()
                    ->sortable(),
               // Tables\Columns\TextColumn::make('presu_gral_ejecucion_material_Pts')
               //     ->numeric()
               //     ->sortable(),
                Tables\Columns\TextColumn::make('por_gastos_generales')
                    ->numeric()
                    ->sortable(),
               // Tables\Columns\TextColumn::make('importe_gastos_generales_Pts')
               //     ->numeric()
               //     ->sortable(),
                Tables\Columns\TextColumn::make('por_beneficio_industriales')
                    ->numeric()
                    ->sortable(),
                //Tables\Columns\TextColumn::make('importe_beneficio_industriales_Pts')
                //    ->numeric()
                //    ->sortable(),
                Tables\Columns\TextColumn::make('por_control_calidad')
                    ->numeric()
                    ->sortable(),
               // Tables\Columns\TextColumn::make('importe_control_calidad_Pts')
               //     ->numeric()
               //     ->sortable(),
                Tables\Columns\TextColumn::make('por_iva')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('iva_Pts')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('por_subcontrata')
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
                Tables\Columns\TextColumn::make('fecha_entrega_proyecto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_recepcion_proyecto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_remision_ayto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_aprobacion_ayto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_pet_rectificacion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_ent_rectificacion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_pet_reforma')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_ent_reforma')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_c_infor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_c_gob')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Punto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_pit_ref')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_eit_ref')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_ci_ref')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_cg_ref')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_dto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nro_dto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('observaciones')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_proyecto')
                    ->searchable(),
                Tables\Columns\IconColumn::make('Requiere_PlanSyS')
                    ->boolean(),
                Tables\Columns\TextColumn::make('importe_proyecto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('presu_gral_ejecucion_material')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('importe_gastos_generales')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('importe_beneficio_industriales')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('importe_control_calidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('iva')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subcontrata')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('honorarios_dir')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('HD_ExcluidoIVA')
                    ->boolean(),
                Tables\Columns\TextColumn::make('honorarios_red')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('HR_ExcluidoIVA')
                    ->boolean(),
                Tables\Columns\TextColumn::make('importePlanSyS')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('Requiere_TramAmbiental')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Expediente')
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
                Tables\Columns\TextColumn::make('Codigo_Plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organismo_direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('director_tecnico')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicioDir.DENOMINACION')
                    ->numeric()
                    ->sortable(),
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
            FasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
