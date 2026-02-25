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
use App\Filament\Obras\Resources\Proyectos\RelationManagers\FasesRelationManager;
use App\Models\Proyecto;
use Filament\Forms;

use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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

        $añoAnterior2 = now()->subYears(10)->year;
        return parent::getEloquentQuery()
        ->select('Proyectos.*') // Selecciona todas las columnas de la tabla "obras"
            //->leftJoin('DatosInicioDeObras', 'DatosInicioDeObras.Expediente', '=', 'Datos_Ejecucion_Obras.Expediente') // Join con la tabla "municipios"
            //->addSelect(trim('TablaDeMunicipios.nombre_municipio'))
           // ->WhereNotNull('carretera');  //->with('municipios');
           ->where('Proyectos.expediente_id', '!=', NULL)
           ->where('Proyectos.AO_PROYECTO', '>=', $añoAnterior2)
           ->where('Proyectos.AO_PROYECTO', '<=', $añoActual);
            //->where('codigo_municipio','=', )

    }
    public static function form(Schema $schema): Schema
    {
        return $schema
        ->columns(7)
            ->components([
                ComponentsSection::make('Datos de Obra')
                ->columnSpanFull()
                ->columns(7)
                ->schema([
                    Select::make('CODIGO_MUNICIPIO')
                    ->relationship('municipios', 'nombre_municipio')
                        ->label('Municipio')
                        ->disabled()
                        ->required(),
                    TextInput::make('AO_PROYECTO')
                    ->label('Año del proyecto')
                        ->required(),
                    TextInput::make('NUMERO_PROYECTO')
                        ->label('Número del proyecto')
                        ->required(),
                    TextInput::make('carretera')
                    ->label('Carretera')
                    ->maxLength(30),
                    Select::make('Servicio_Gestor')
                        ->relationship('ServicioGestor', 'DENOMINACION')
                        ->label('Servicio Gestor')
                        ->disabled(),
                ]),
                ComponentsSection::make('Datos del Proyecto')
                    ->columnSpanFull()
                    ->columns(7)
                    ->schema([

                        Toggle::make('Compartido')
                            ->required(),
                        TextInput::make('den_proyecto')
                            ->maxLength(1000),

                        Select::make('organismo_redactor')
                            ->relationship('organismoRed', 'denominacion')
                            ->default('DP')
                            ->label('Organismo redactor'),
                        Select::make('Servicio_redactor')
                            ->relationship('servicioRed', 'DENOMINACION')
                            ->label('Servicio Redactor'),
                        TextInput::make('autor')
                            ->maxLength(100),
                        TextInput::make('ColegioOficial')
                            ->maxLength(2),
                        Toggle::make('SubvencionEconRedaccion')
                            ->required(),
                    ]),

                TextInput::make('plazo')
                    ->numeric(),
                Select::make('UnidadPlazo')
                    ->options([
                        'd' => 'Días',
                        'm' => 'Meses',
                        'a' => 'Años',
                    ])
                    ->default('m'),
                TextInput::make('nro_ejemplares')
                    ->numeric(),
               /* TextInput::make('grupo')
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
                    ->numeric(),*/
                ComponentsSection::make('Importes del Proyecto')
                    ->columnSpanFull()
                    ->columns(7)
                    ->schema([
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
                TextInput::make('por_gastos_generales')
                    ->numeric(),

                TextInput::make('por_beneficio_industriales')
                    ->numeric(),

                TextInput::make('por_control_calidad')
                    ->numeric(),

                TextInput::make('por_iva')
                    ->numeric(),

                TextInput::make('por_subcontrata')
                    ->numeric(),

                    ]),
                 ComponentsSection::make('Fechas del Proyecto')
                    ->columnSpanFull()
                    ->columns(7)
                    ->schema([
                        DateTimePicker  ::make('fecha_entrega_proyecto'),
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
                    ]),
                TextInput::make('nro_dto')
                    ->numeric(),
                TextInput::make('observaciones')
                    ->maxLength(1000),
                TextInput::make('estado_proyecto')
                    ->maxLength(50),
               Select::make('Requiere_PlanSyS')
                    ->options([
                        '1' => 'SI',
                        '0' => 'NO',
                    ])
                    ->required(),

                Select::make('Requiere_TramAmbiental')
                    ->options([
                        '1' => 'SI',
                        '0' => 'NO',
                    ])
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
                Select::make('organismo_direccion')
                    ->relationship('organismoDir', 'denominacion')
                    ->label('Organismo Dirección'),

                TextInput::make('director_tecnico')
                   ->required(),
                Select::make('servicio_direccion')
                    ->relationship('servicioDir', 'DENOMINACION')
                    ->label('Servicio Dirección'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('municipio.nombre_municipio')
                    ->numeric()
                    ->label('Municipio')
                    ->sortable()
                   ->searchable(),
                TextColumn::make('AO_PROYECTO')
                    ->label('Año')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('NUMERO_PROYECTO')
                    ->label('Número')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('servicioGestor.DENOMINACION')
                ->label('Servicio Gestor')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                IconColumn::make('Compartido')
                    ->boolean(),
                TextColumn::make('den_proyecto')
                    ->label('Denominación del proyecto')
                    ->tooltip(function ( TextColumn $column): ?string {
                        $state = $column->getState(); // Obtiene el texto completo
                        if (strlen($state) <= 50) {
                            return null; // No muestra tooltip si el texto no está truncado
                        }
                        return $state;
                        })
                    ->searchable()
                    ->sortable(),
                //Tables\Columns\TextColumn::make('importe_proyecto_Pts')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('organismoRed.denominacion')
                    ->label('Organismo redactor')
                    ->sortable(),
                TextColumn::make('servicioRed.DENOMINACION')
                    ->numeric()
                    ->label('Servicio Redactor')
                    ->sortable(),
                TextColumn::make('autor')
                    ->label('Autor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ColegioOficial')
                    ->label('Colegio Oficial')
                    ->sortable(),
                IconColumn::make('SubvencionEconRedaccion')
                    ->label('Subvención Económica Redacción')
                    ->boolean(),
                TextColumn::make('carretera')
                    ->label('Carretera')
                    ->sortable(),
                TextColumn::make('plazo')
                    ->label('Plazo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('UnidadPlazo')
                    ->label('Unidad plazo')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'm' => 'Meses',
                            'd' => 'Días',
                            'a' => 'Años',
                            default => $state,
                        };
                    })

                    ->sortable(),
                TextColumn::make('nro_ejemplares')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('grupo')
                    ->sortable(),
                TextColumn::make('subgrupo')
                    ->sortable(),
                TextColumn::make('categoria')
                    ->sortable(),
                TextColumn::make('revision')
                    ->sortable(),
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
                     ->searchable()
                    ->sortable(),
                TextColumn::make('fecha_recepcion_proyecto')
                     ->searchable()
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
                    ->sortable(),
                IconColumn::make('Requiere_PlanSyS')
                    ->label('Requiere Plan SyS')
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
                TextColumn::make('expediente_id')
                    ->sortable(),
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
                TextColumn::make('organismoDir.denominacion')
                    ->label('Organismo Dirección')
                    ->searchable(),
                TextColumn::make('director_tecnico')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('servicioDir.DENOMINACION')
                    ->label('Servicio Dirección')
                    ->searchable(),
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
