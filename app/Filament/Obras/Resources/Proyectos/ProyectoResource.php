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
use App\Filament\Obras\Resources\Concerns\HasAssignedExpedienteVisibility;
use App\Filament\Obras\Resources\Proyectos\Pages\ListProyectos;
use App\Filament\Obras\Resources\Proyectos\Pages\CreateProyecto;
use App\Filament\Obras\Resources\Proyectos\Pages\EditProyecto;
use App\Models\DatosDeInicioDeObras;
use App\Models\Expediente;
use App\Models\PorcentajesProyectos;
use App\Models\Proyecto;
use Filament\Forms;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Closure;
use Illuminate\Validation\ValidationException;
class ProyectoResource extends Resource
{
    use HasAssignedExpedienteVisibility;

    protected static ?string $model = Proyecto::class;
    protected static array $formaEjecucionCache = [];

    protected static ?string $modelLabel = 'Proyecto';
    protected static ?string $pluralModelLabel = 'Proyectos';
    // protected static ?string $tenantOwnershipRelationshipName = 'team';
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
           ->where('Proyectos.AO_PROYECTO', '>=', $añoAnterior2)
           ->where('Proyectos.AO_PROYECTO', '<=', $añoActual)
           ->orderByDesc('Proyectos.AO_PROYECTO');
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
                    TextInput::make('expediente_id')
                    ->label('Número de expediente')
                    ->default(fn (?Proyecto $record) => $record?->expediente_id)
                    ->afterStateHydrated(function (TextInput $component, mixed $state, ?Proyecto $record): void {
                        $component->state(self::normalizeExpedienteId($state) ?? $record?->expediente_id);
                    })
                    ->disabled()
                    ->dehydrated(),
                    Select::make('CODIGO_MUNICIPIO')
                    ->relationship('municipio', 'nombre_municipio')
                        ->label('Municipio')
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                    TextInput::make('AO_PROYECTO')
                    ->label('Año del proyecto')
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                    TextInput::make('NUMERO_PROYECTO')
                        ->label('Número del proyecto')
                        ->required(),
                    Placeholder::make('referencia_completa')
                        ->label('Plan-Obra-Subref-Año ejecución')
                        ->content(function (callable $get): string {
                            $plan = trim((string) ($get('Codigo_Plan') ?? '-'));
                            $obra = trim((string) ($get('referencia') ?? '-'));
                            $subreferencia = trim((string) ($get('subreferencia') ?? '-'));
                            $aoEjecucion = trim((string) ($get('ao_ejecucion') ?? '-'));

                            $plan = $plan === '' ? '-' : $plan;
                            $obra = $obra === '' ? '-' : $obra;
                            $subreferencia = $subreferencia === '' ? '-' : $subreferencia;
                            $aoEjecucion = $aoEjecucion === '' ? '-' : $aoEjecucion;

                            return "{$plan}-{$obra}-{$subreferencia}-{$aoEjecucion}";
                        }),
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
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),
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
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),
                TextInput::make('honorarios_dir')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),
                Toggle::make('HD_ExcluidoIVA')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),
                TextInput::make('honorarios_red')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),
                Toggle::make('HR_ExcluidoIVA')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),
                TextInput::make('importePlanSyS')
                    ->numeric(),
                TextInput::make('por_gastos_generales')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),

                TextInput::make('por_beneficio_industriales')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),

                TextInput::make('por_control_calidad')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),

                TextInput::make('por_iva')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),

                TextInput::make('por_subcontrata')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),

                Toggle::make('ajuste_importes')
                    ->label('Ajuste manual de importes')
                    ->dehydrated(false)
                    ->default(false)
                    ->live()
                    ->afterStateUpdated(function (callable $get, callable $set): void {
                        self::recalculateFromFormState($get, $set);
                    }),

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
                    ->relationship('team', 'name')
                    ->hidden(true),
                TextInput::make('Codigo_Plan')
                    ->maxLength(14)
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('referencia')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('subreferencia')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('ao_ejecucion')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
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
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable(),
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
                self::assignedExpedientesFilter(),
                Filter::make('expediente_id')
                    ->label('Número de expediente')
                    ->schema([
                        TextInput::make('expediente_id')
                            ->label('Nº expediente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = trim((string) ($data['expediente_id'] ?? ''));

                        if ($value === '') {
                            return $query;
                        }

                        return $query->where('expediente_id', 'like', "%{$value}%");
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $value = trim((string) ($data['expediente_id'] ?? ''));

                        return $value !== '' ? 'Expediente: ' . $value : null;
                    }),
                Tables\Filters\SelectFilter::make('AO_PROYECTO')
                    ->label('Año')
                    ->options(fn (): array => Proyecto::query()
                        ->whereNotNull('expediente_id')
                        ->whereNotNull('AO_PROYECTO')
                        ->where('AO_PROYECTO', '>=', now()->subYears(10)->year)
                        ->where('AO_PROYECTO', '<=', now()->year)
                        ->distinct()
                        ->orderByDesc('AO_PROYECTO')
                        ->pluck('AO_PROYECTO', 'AO_PROYECTO')
                        ->all())
                    ->searchable(),
                Tables\Filters\SelectFilter::make('CODIGO_MUNICIPIO')
                    ->label('Municipio')
                    ->relationship('municipio', 'nombre_municipio')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('Servicio_Redactor')
                    ->label('Serv. Redactor')
                    ->options(fn (): array => Proyecto::query()
                        ->leftJoin('TablaDeDepartamentos as ServicioRed', 'ServicioRed.CODIGO_DPTO', '=', 'Proyectos.Servicio_redactor')
                        ->select('Proyectos.Servicio_redactor', 'ServicioRed.DENOMINACION')
                        ->whereNotNull('expediente_id')
                        ->whereNotNull('Servicio_redactor')
                        ->distinct()
                        ->orderByDesc('Servicio_redactor')
                        ->pluck('ServicioRed.DENOMINACION', 'Proyectos.Servicio_redactor'   )
                        ->all())
                    ->searchable(),
            ])
            ->recordUrl(fn (Proyecto $record): string => static::getUrl('edit', [
                'record' => static::buildRouteRecordKey($record),
            ]))
            ->recordActions([
                EditAction::make()
                    ->url(fn (Proyecto $record): string => static::getUrl('edit', [
                        'record' => static::buildRouteRecordKey($record),
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function buildRouteRecordKey(Proyecto $record): string
    {
        $municipio = trim((string) ($record->CODIGO_MUNICIPIO ?? ''));
        $aoProyecto = trim((string) ($record->AO_PROYECTO ?? ''));
        $numeroProyecto = trim((string) ($record->NUMERO_PROYECTO ?? ''));

        return implode('|', [$municipio, $aoProyecto, $numeroProyecto]);
    }

    private static function parseRouteRecordKey(string $key): ?array
    {
        $parts = explode('|', $key);

        if (count($parts) !== 3) {
            return null;
        }

        [$municipio, $aoProyecto, $numeroProyecto] = $parts;

        if ($municipio === '' || $aoProyecto === '' || $numeroProyecto === '') {
            return null;
        }

        return [
            'CODIGO_MUNICIPIO' => $municipio,
            'AO_PROYECTO' => $aoProyecto,
            'NUMERO_PROYECTO' => $numeroProyecto,
        ];
    }

    public static function resolveRecordRouteBinding(int | string $key, ?Closure $modifyQuery = null): ?Model
    {
        $parsedKey = static::parseRouteRecordKey((string) $key);

        if ($parsedKey === null) {
            return null;
        }

        $query = Proyecto::query()
            ->where('CODIGO_MUNICIPIO', $parsedKey['CODIGO_MUNICIPIO'])
            ->where('AO_PROYECTO', $parsedKey['AO_PROYECTO'])
            ->where('NUMERO_PROYECTO', $parsedKey['NUMERO_PROYECTO']);

        if ($modifyQuery !== null) {
            $query = $modifyQuery($query) ?? $query;
        }

        return $query->first();
    }

    public static function getRelations(): array
    {
        return [
            //
           // FasesRelationManager::class,
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

    public static function prepareFinancialDataBeforeSave(array $data): array
    {
        $expedienteId = self::normalizeExpedienteId($data['expediente_id'] ?? null);
        $data['expediente_id'] = $expedienteId;
        $formaEjecucion = self::resolveFormaEjecucion($expedienteId);
        $aoProyecto = $data['AO_PROYECTO'] ?? $data['ao_proyecto'] ?? null;

        $defaults = PorcentajesProyectos::defaultsForAoProyecto($aoProyecto);
        foreach ($defaults as $field => $value) {
            if (! array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
                $data[$field] = $value;
            }
        }

        $data = self::applyAuxiliaryPercentages($data, $defaults);

        if (! self::toBool($data['ajuste_importes'] ?? false)) {
            $data = array_merge($data, self::calculateImportes($data, $formaEjecucion));
        }

        self::validateFinancialData($data, $formaEjecucion);
        unset($data['ajuste_importes']);

        return $data;
    }

    public static function recalculateFromFormState(callable $get, callable $set): void
    {
        // Get and normalize expediente_id early - if invalid, we can't proceed
        $expedienteId = self::normalizeExpedienteId($get('expediente_id'));

        // If we don't have a valid expediente_id, skip recalculation that requires it
        // The form will be validated on save by EditProyecto.fillForm()
        if ($expedienteId === null) {
            return;
        }

        $state = [
            'expediente_id' => $expedienteId,
            'AO_PROYECTO' => $get('AO_PROYECTO'),
            'importe_proyecto' => $get('importe_proyecto'),
            'presu_gral_ejecucion_material' => $get('presu_gral_ejecucion_material'),
            'importe_gastos_generales' => $get('importe_gastos_generales'),
            'importe_beneficio_industriales' => $get('importe_beneficio_industriales'),
            'importe_control_calidad' => $get('importe_control_calidad'),
            'iva' => $get('iva'),
            'subcontrata' => $get('subcontrata'),
            'honorarios_dir' => $get('honorarios_dir'),
            'honorarios_red' => $get('honorarios_red'),
            'importePlanSyS' => $get('importePlanSyS'),
            'HD_ExcluidoIVA' => $get('HD_ExcluidoIVA'),
            'HR_ExcluidoIVA' => $get('HR_ExcluidoIVA'),
            'por_gastos_generales' => $get('por_gastos_generales'),
            'por_beneficio_industriales' => $get('por_beneficio_industriales'),
            'por_control_calidad' => $get('por_control_calidad'),
            'por_iva' => $get('por_iva'),
            'por_subcontrata' => $get('por_subcontrata'),
            'ajuste_importes' => $get('ajuste_importes'),
        ];

        $formaEjecucion = self::resolveFormaEjecucion($state['expediente_id']);
        $defaults = PorcentajesProyectos::defaultsForAoProyecto($state['AO_PROYECTO'] ?? null);
        $state = self::applyAuxiliaryPercentages($state, $defaults);
        if (array_key_exists('honorarios_dir', $state)) {
            $set('honorarios_dir', $state['honorarios_dir']);
        }
        if (array_key_exists('honorarios_red', $state)) {
            $set('honorarios_red', $state['honorarios_red']);
        }
        if (array_key_exists('importePlanSyS', $state)) {
            $set('importePlanSyS', $state['importePlanSyS']);
        }

        if (! self::toBool($state['ajuste_importes'] ?? false)) {
            $calculated = self::calculateImportes($state, $formaEjecucion);
            foreach ($calculated as $field => $value) {
                $set($field, $value);
            }

            if (in_array($formaEjecucion, ['DIP', 'AYC'], true)) {
                $set('por_subcontrata', 0.0);
            }
        }
    }

    public static function validateFinancialData(array $data, ?string $formaEjecucion): void
    {
        if (! self::toBool($data['ajuste_importes'] ?? false)) {
            $sumPorcentajes = self::toFloat($data['por_gastos_generales'] ?? 0)
                + self::toFloat($data['por_beneficio_industriales'] ?? 0)
                + self::toFloat($data['por_control_calidad'] ?? 0)
                + self::toFloat($data['por_iva'] ?? 0)
                + (self::toFloat($data['por_subcontrata'] ?? 0) / 2);

            if ($sumPorcentajes > 100.0) {
                throw ValidationException::withMessages(
                    [
                    'por_subcontrata' => 'La suma de porcentajes (GG + BI + CC + IVA + Subcontrata/2) no puede superar 100.',
                ]);
            }

            if (($formaEjecucion === null || $formaEjecucion === '') && self::toFloat($data['importe_proyecto'] ?? 0) > 0) {
                throw ValidationException::withMessages([
                    'expediente_id' => 'No existe forma de ejecución para este expediente.',
                ]);
            }

            return;
        }

        $importe = self::toFloat($data['importe_proyecto'] ?? 0);
        $totalManual = self::toFloat($data['iva'] ?? 0)
            + self::toFloat($data['honorarios_dir'] ?? 0)
            + self::toFloat($data['honorarios_red'] ?? 0)
            + self::toFloat($data['subcontrata'] ?? 0)
            + self::toFloat($data['importe_control_calidad'] ?? 0)
            + self::toFloat($data['importe_beneficio_industriales'] ?? 0)
            + self::toFloat($data['importe_gastos_generales'] ?? 0)
            + self::toFloat($data['presu_gral_ejecucion_material'] ?? 0);

        if (abs(round($totalManual, 2) - round($importe, 2)) > 0.01) {
            throw ValidationException::withMessages([
                'presu_gral_ejecucion_material' => 'En ajuste manual, la suma de importes (sin Plan SyS) debe ser igual al importe del proyecto.',
            ]);
        }
    }

    private static function calculateImportes(array $data, ?string $formaEjecucion): array
    {
        $importe = self::toFloat($data['importe_proyecto'] ?? 0);
        $honDir = self::toFloat($data['honorarios_dir'] ?? 0);
        $honRed = self::toFloat($data['honorarios_red'] ?? 0);

        $pgg = self::toFloat($data['por_gastos_generales'] ?? 0);
        $pbi = self::toFloat($data['por_beneficio_industriales'] ?? 0);
        $pcc = self::toFloat($data['por_control_calidad'] ?? 0);
        $piv = self::toFloat($data['por_iva'] ?? 0);
        $psu = self::toFloat($data['por_subcontrata'] ?? 0);

        $honDirIncluyeIva = ! self::toBool($data['HD_ExcluidoIVA'] ?? false);
        $honRedIncluyeIva = ! self::toBool($data['HR_ExcluidoIVA'] ?? false);

        $iva = 0.0;
        $gastos = 0.0;
        $beneficios = 0.0;
        $calidad = 0.0;
        $subcontrata = self::toFloat($data['subcontrata'] ?? 0);
        $material = self::toFloat($data['presu_gral_ejecucion_material'] ?? 0);

        if (in_array($formaEjecucion, ['DIP', 'AYC'], true)) {
            $psu = 0.0;
            $subcontrata = 0.0;

            if ($piv == 0.0) {
                $iva = 0.0;
            } else {
                $sinHonorarios = ($honDir == 0.0 && $honRed == 0.0);
                $honDirConIva = ($honDir != 0.0 && $honDirIncluyeIva);
                $honRedConIva = ($honRed != 0.0 && $honRedIncluyeIva);
                $honorariosSinIvaAmbos = ($honDir != 0.0 && ! $honDirIncluyeIva) && ($honRed != 0.0 && ! $honRedIncluyeIva);

                if ($sinHonorarios || $honDirConIva || $honRedConIva) {
                    $iva = ($importe - ($honDir + $honRed)) * $piv / (100 + $piv);
                } elseif ($honorariosSinIvaAmbos) {
                    $iva = $importe * $piv / (100 + $piv);
                }
            }

            $base = $importe - ($iva + $honDir + $honRed);
            $den = 100 + $pgg + $pcc + $pbi;

            if ($den > 0) {
                $gastos = $base * $pgg / $den;
                $beneficios = $base * $pbi / $den;
                $calidad = $base * $pcc / $den;
            }

            $material = $importe - ($honDir + $honRed + $iva + $beneficios + $calidad + $gastos);
        } elseif ($formaEjecucion === 'AYA') {
            $pgg = 0.0;
            $pbi = 0.0;
            $pcc = 0.0;
            $gastos = 0.0;
            $beneficios = 0.0;
            $calidad = 0.0;

            if ($psu == 0.0) {
                $piv = 0.0;
            }

            if ($piv == 0.0 && $psu == 0.0) {
                $material = $importe - ($honDir + $honRed);
            } elseif ($piv == 0.0 && $psu > 0.0) {
                $material = ($importe - ($honDir + $honRed)) * 100 / (100 + ($psu / 2));
            } else {
                $material = ($importe - ($honDir + $honRed)) * 100 / (100 + ($piv / 2) + (($psu / 2) * $piv / 100) + ($psu / 2));
            }

            $subcontrata = $psu > 0 ? ($material / 2) * $psu / 100 : 0.0;
            $iva = ($psu > 0 && $piv > 0) ? (($material / 2) + $subcontrata) * $piv / 100 : 0.0;
        } else {
            $base = max($importe - $honDir - $honRed, 0);
            $gastos = $base * ($pgg / 100);
            $beneficios = $base * ($pbi / 100);
            $calidad = $base * ($pcc / 100);
            $subcontrata = $base * ($psu / 100);
            $iva = max($importe - ($honDir + $honRed + $gastos + $beneficios + $calidad + $subcontrata), 0);
            $material = $importe - ($honDir + $honRed + $iva + $beneficios + $calidad + $gastos);
        }

        return [
            'por_gastos_generales' => self::round2($pgg),
            'por_beneficio_industriales' => self::round2($pbi),
            'por_control_calidad' => self::round2($pcc),
            'por_iva' => self::round2($piv),
            'por_subcontrata' => self::round2($psu),
            'importe_gastos_generales' => self::round2($gastos),
            'importe_beneficio_industriales' => self::round2($beneficios),
            'importe_control_calidad' => self::round2($calidad),
            'iva' => self::round2($iva),
            'subcontrata' => self::round2($subcontrata),
            'presu_gral_ejecucion_material' => self::round2($material),
        ];
    }

    private static function resolveFormaEjecucion(?string $expedienteId): ?string
    {
        $expedienteId = self::normalizeExpedienteId($expedienteId);

        if ($expedienteId === null) {
            return null;
        }

        if (array_key_exists($expedienteId, self::$formaEjecucionCache)) {
            return self::$formaEjecucionCache[$expedienteId];
        }

        $forma = DatosDeInicioDeObras::query()
            ->where('expediente_id', $expedienteId)
            ->value('forma_ejecucion');

        if ($forma === null || $forma === '') {
            $forma = Expediente::query()
                ->where('expediente_id', $expedienteId)
                ->value('forma_ejecucion');
        }

        self::$formaEjecucionCache[$expedienteId] = $forma;

        return $forma;
    }

    private static function toFloat(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        return (float) $value;
    }

    private static function round2(float $value): float
    {
        return round($value, 2);
    }

    private static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array($value, [1, '1', 'true', 'on', 'yes'], true);
    }

    private static function applyAuxiliaryPercentages(array $data, array $defaults): array
    {
        $importeProyecto = self::toFloat($data['importe_proyecto'] ?? 0);

        $porHd = self::toFloat($defaults['por_honorarios_dir'] ?? $data['por_honorarios_dir'] ?? 0);
        $porHr = self::toFloat($defaults['por_honorarios_red'] ?? $data['por_honorarios_red'] ?? 0);
        $porBt = self::toFloat($defaults['por_plan_sys'] ?? $data['por_plan_sys'] ?? 0);

        if (self::isMissing($data['honorarios_dir'] ?? null) && $porHd > 0) {
            $data['honorarios_dir'] = self::round2($importeProyecto * $porHd / 100);
        }

        if (self::isMissing($data['honorarios_red'] ?? null) && $porHr > 0) {
            $data['honorarios_red'] = self::round2($importeProyecto * $porHr / 100);
        }

        if (self::isMissing($data['importePlanSyS'] ?? null) && $porBt > 0) {
            $data['importePlanSyS'] = self::round2($importeProyecto * $porBt / 100);
        }

        return $data;
    }

    private static function isMissing(mixed $value): bool
    {
        return $value === null || $value === '';
    }

    private static function normalizeExpedienteId(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === '' || $value === 0 || $value === '0') {
            return null;
        }

        return (string) $value;
    }
}
