<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObras;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\ListDatosDeInicioDeObras;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\CreateDatosDeInicioDeObra;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\EditDatosDeInicioDeObra;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\ViewInicioDeObra;

use App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\ImportesPorOrganismoRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\AyudaRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\DocumentosRelationManager;
use App\Filament\Obras\Resources\Concerns\HasAssignedExpedienteVisibility;
use App\Filament\Obras\Resources\Concerns\HasWordTemplatePrinting;
use App\Filament\Traits\ZonasFilter;
use App\Forms\Components\ImportesManagement;
use App\Models\DatosDeInicioDeObras;
use App\Services\Importes\ImportesManagementRepository;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource as BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Forms\Components\ObraGeneralInfo;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Traits\CommonFilters;
use App\Filament\Traits\MunicipiosFilter;
use App\Filament\Widgets\DocumentosTable;
//use Filament\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder;
use Illuminate\Validation\ValidationException;

/**
 * Resource Filament para la gestión operativa de inicios de obra.
 *
 * Reúne la edición de datos generales, ayuda técnica, fechas clave, importes
 * por organismo y acciones documentales sobre el expediente de obra.
 */
class DatosDeInicioDeObrasResource extends BaseResource
{
    use MunicipiosFilter;
    use CommonFilters;
    use ZonasFilter;
    use HasAssignedExpedienteVisibility;
    use HasWordTemplatePrinting;
    protected static ?string $model = DatosDeInicioDeObras::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?int $navigationSort = 1;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationLabel ='Inicio de Obras';**/
    protected static ?string $modelLabel = 'Inicio de Obras';
    protected static ?string $pluralModelLabel = 'Inicios de Obras';
    protected static array $searchableAttributes = [
        'carretera',
        'municipios.nombre',
        'Codigo_Plan',
        'ao_ejecuciion',
        'expediente_id',
    ];

    /**
     * Personaliza la consulta base del listado para limitar años y añadir joins de apoyo.
     */
    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYears(5)->year;


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
    /**
     * Define el formulario principal del recurso, agrupado por bloques funcionales.
     */
    public static function form(Schema $schema): Schema
    {
    //$record=$schema->getRecord();
    $record = $schema->getLivewire()->getRecord();
    //dd($record->planes->denominacion_plan ?? 'sin plan');
        return $schema
            ->columns(2)
            ->components([
                //
            ObraGeneralInfo::make('informacion_general')
                ->label('Información General de la Obra')
                ->columnSpan(1)
                ->SetObraData($record ?? null),
            // ImportesInfo::make('importes')
            //     ->label('Importes de la Obra')
            //     ->columnSpan(1)
            //     ->setImportesDeObra($record?->importes ?? $record?->importesPorOrganismo?->first()),
            Livewire::make(DocumentosTable::class, [
                'expedienteSeleccionado' => (string) ($record?->expediente_id ?? ''),
            ])
                ->columnSpan(1),
            ImportesManagement::make('importes_management')
                ->label('Gestión de importes por organismo')
                ->columnSpanFull()
                ->setImportesContext((string) ($record?->expediente_id ?? '')),
           /* Forms\Components\Select::make('municipio')
                ->label('Municipio')
                ->relationship('municipios', 'nombre_municipio')
                ->disabled()
                //->hidden()
                ->extraAttributes(['class' => 'custom-select-class'])
                ->visible(fn ($get) => $get('municipio'))
                ->reactive()    , // Hace que el campo sea reactivo
                */

            Section::make('Datos de Obra')
                ->columnSpanFull()
                ->columns(7)
                ->schema([
                Select::make('TipoActuacion')
                    ->label('Tipo de Actuación')
                    ->relationship('tipoactuacion', 'descripcion_actuacion')
                    ->columnSpan(2),
                    //->dehydrated(false)
                   // ->extraAttributes(['class' => 'compact-select']),
                TextInput::make('TipoObra')
                    ->nullable()
                    ->label('Tipo de Obra'),

                    //->required()

                Select::make('LicenciaObra')
                    ->label('Licencia de Obra')
                    ->options([
                            'SI' => 'SI',
                            'NO' => 'NO',
                            'NR' => 'NR'                    ]),
                Select::make('disponibilidad_terreno')
                    ->label('Disp. Terrenos')
                    ->nullable()
                    //->extraAttributes(['class' => 'compact-select'])
                    ->options([
                        'SI' => 'SI',
                        'NO' => 'NO',
                        'NR' => 'NR'                    ]),
                    //->required()
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->required(),
                TextInput::make('PartidaPresupuesto')
                    ->nullable()
                    ->label('Partida Presupuestaria'),
                TextInput::make('peticion_ayuda_tec')
                    ->nullable()
                    ->label('Petición Ayuda técnica'),
                Textarea::make('EstadoServicioTecnico')
                    ->label('Estado Servicio Tecnico')
                    ->nullable(),

                Textarea::make('EstadoServicioAdministrativo')
                    ->label('Estado Servicio Admvo.')
                    ->nullable(),
                Textarea::make('comentario')
                    ->label('Comentario')
                    ->nullable(),
                Select::make('TipoPrograma')
                    ->label('Tipo Programa')
                    ->options([
                        'Concertado' => 'Concertado',
                        'No-Concertado ' => 'NO_Concertado',

                    ]),
                Select::make('Clasificacion')
                    ->label('Clasificación.')
                    ->reactive()
                    ->dehydrated(false)
                    ->options([
                        'SI' => 'SI',
                        'NO' => 'NO',
                        'NR' => 'NR'                    ]),
                TextInput::make('importes.importe_aprobado')
                            ->label('Importe Aprobado')
                            ->nullable()
                            ->numeric(),
                Radio::make('Aportacion')
                    ->label('Aportación del Ayto.')
                    ->default('NO')
                    ->reactive()
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($state, callable $set){
                        //dd($state);
                        //$set('CompApAyto',  $state === 'SI' ? 'visible' : 'hidden');
                        $set('CompApAyto', null);
                        $set('CompApAyto',  $state === 'SI' ? 'visible' : 'hidden');
        // Ocultar Patronato y campos relacionados
                        $set('Patronato', 'hidden');
                    })
                    ->options([
                        'SI' => 'SI',
                        'NO' => 'NO',
                    ]),

                Radio::make('CompApAyto')
                        ->label('Forma de Aportación')
                        ->reactive()
                        ->visible(fn ($get) => $get('Aportacion')==='SI')
                        //->dehydrateStateUsing(function ($state, $get) {
                            // Si el campo "forma" está vacío, guarda el valor del campo Radio
                           // dd($get('Aportacion'));
                        //    return empty($state) ? $get('Aportacion') : $state;
                        //})
                        ->afterStateUpdated(function ($state, callable $set){
                            // dd($state);
                            $set('Patronato',  $state === 'RE' ? 'visible' : 'hidden');
                            $set('FechaComPatronato',  $state === 'RE' ? 'visible' : 'hidden');
                            $set('FechaIngresoAyto',  $state === 'RE' ? 'visible' : 'hidden');
                            $set('Aceptacion',  $state === 'RE' ? 'visible' : 'hidden');

                         })
                        ->options([
                            'RE' => 'Retraer de Recaudación',
                            'IN' => 'Ingreso del Ayto.',
                            'EF' => 'Entidad financiera',

                    ]),


                    Fieldset::make('Patronato')
                        ->label('Patronato')
                        ->columns(3 )
                        ->columnSpan(3)
                        ->visible(fn ($get) => $get('CompApAyto') ==='RE')
                            ->schema([
                                DatePicker::make('FechaComPatronato')
                                    ->label('Fecha Com. Patronato')
                                    ->nullable(),
                                Select::make('Aceptacion')
                                    ->label('Aceptación')
                                    ->options([
                                        'SI' => 'SI',
                                        'NO ' => 'NO'
                                    ]),
                                DatePicker::make('FechaIngresoAyto')
                                    ->label('Fecha Ingreso Ayto.')
                                    ->nullable(),

                            ]),



                ]),
                Section::make('Fechas')
                ->columnSpanFull()
                ->columns(4)
                //->id('ayuda')
                //->relationship('ayuda')
                ->schema([
                    DatePicker::make('fecha_notificacion_ayto')
                    ->nullable()
                    ->label('Notifif. Ayuntamiento'),
                    DatePicker::make('fecha_pet_acta_replanteo')
                    ->label('Petición Repl. Previo')
                    ->reactive()
                    ->suffixAction(
                        self::makeWordPrintAction(
                            name: 'imprimir_notificacion',
                            label: 'Imprimir Notificación',
                            icon: 'heroicon-m-envelope',
                            documentName: 'Petición Ayuda Técnica',
                            requiredStatePath: 'fecha_pet_acta_replanteo',
                            requiredMessage: 'Debe completar la fecha de notificación primero',
                            fallbackTemplatePath: 'Modelos_Doc\\Petición Ayuda Técnica.dotx',
                            downloadBaseName: 'peticion-ayuda-tecnica',
                        ))
                    ->required(),
                DatePicker::make('fecha_acta_replanteo_previo')
                    ->nullable()
                    ->label('Acta de Repl. Previo'),
                DatePicker::make('fecha_rem_pet_ayuda')
                    ->nullable()
                    ->label('Petición de ayuda técnica'),
                DatePicker::make('fecha_envio_fiscalizacion')
                    ->nullable()
                    ->label('Envio a Fiscalizar'),
                DatePicker::make('fecha_fiscalizacion')
                    ->nullable()
                    ->label('Fecha Fiscalización'),
                DatePicker::make('fecha_prev_comienzo_obra')
                    ->nullable()
                    ->label('Prevista Comienzo'),
                DatePicker::make('fecha_prev_term_obra')
                    ->nullable()
                    ->label('Prevista Terminación'),
                DatePicker::make('FechaMediosMatAyto')
                    ->nullable()
                    ->label('Medios Materiales Ayto.'),
                ]),

            Section::make('Ayuda Técnica')
                ->columnSpanFull()
                ->columns(4)
                ->collapsible()
                ->id('ayuda_tecnica')
                ->relationship('ayudaTecnica')
                ->schema([
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->dehydrateStateUsing(function ($state, $get) {

                       //dd($state);
                        return empty($state) ? $get('expediente_id') : $state;
                    })
                    ->disabled(),

                Select::make('dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayudaR', 'DENOMINACION')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        if (filled($state)) {
                            $set('AyuTecRed', 'SI');
                        }
                    })
                    ->extraInputAttributes(['class' => '!h-9 !py-1 !text-sm !leading-none']),

                Select::make('departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayudaD', 'DENOMINACION')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        if (filled($state)) {
                            $set('AyuTecDir', 'SI');
                        }
                    }),

                TextInput::make('AyuTecRed')
                    ->label('Ayuda Técnica a la Redacción')
                    ->extraInputAttributes(['class' => 'bg-red']),

                TextInput::make('AyuTecDir')
                ->label('Ayuda Técnica a la Dirección'),

                TextInput::make('SubvencionEconomicaR')
                    ->label('Subvención en Redacción'),

                TextInput::make('SubvencionEconomicaD')
                    ->label('Subvención en Dirección'),


                ]),

            ]);
    }

    /**
     * Configura la tabla de consulta con columnas descriptivas y filtros reutilizables.
     */
    public static function table(Table $table): Table
    {

        return $table
        ->defaultSort('ao_ejecucion', 'desc')
        ->contentGrid([
            'md' => 1, // una sola columna
        ])

        ->striped() // Filas alternas tipo Excel
        //->paginated(false) // Mostrar todas las filas
            ->columns([
                //

            TextColumn:: make('Codigo_Plan')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
            TextColumn::make('numero_obra')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('subreferencia')
                ->searchable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('ao_ejecucion')
                ->sortable()
                //->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('Obra')
                    ->label('Obra')
                     ->sortable()
                    /*->searchable([
                        'Codigo_Plan',
                        'numero_obra',
                        'subreferencia',
                        'ao_ejecucion'
                    ])*/
                   // ->grow()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->getStateUsing(function ($record) {
                        return $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
                    }),
            TextColumn::make('nombre_obra1')
            ->label('Nombre')
            ->limit(50, '... [más]') // Limita a 50 caracteres y usa "... [más]" como indicador de truncamiento
            ->tooltip(function ( TextColumn $column): ?string {
                $state = $column->getState(); // Obtiene el texto completo
                if (strlen($state) <= 50) {
                    return null; // No muestra tooltip si el texto no está truncado
                }
                return $state;
                })
                ->sortable()
                ->grow()
                ->searchable()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('Ubicacion')
                ->label('Ubicación')
                ->getStateUsing(function ($record) {
                 // dd($record->municipios);
                 return ($record->municipio !== null && $record->municipio !== 0)
                 ? $record->municipios->nombre_municipio
                 : $record->carretera;
                    //return $record->municipios->nombre_municipio ?: $record->carretera;
                }),
                //->searchable(),

            TextColumn::make('municipios.nombre_municipio')
                ->sortable()
                ->searchable()
                ->hidden()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('carretera')
                ->sortable()
                ->searchable()
                ->hidden()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
           TextColumn::make('estados.estado_abrev')
                ->sortable()
                ->searchable()
                ->grow()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('forma_ejecucion')
                ->Label('F.Ejecuc.')
                ->sortable()
                ->width(50)
                ->searchable()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('municipios.zonas.ZONA')
                ->label('Zona')
                ->sortable()
                ->searchable()
                ->grow()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('expediente_id')
                ->label('Expediente')
                ->sortable()
               // ->searchable()
                ->grow()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false)
            ])

            ->filters([
            self::assignedExpedientesFilter(),
            ...self::getCommonFilters(),
            self::getMunicipioFromInicioObrasFilter(),
            self::getZonaFromInicioObrasFilter(),
           ],layout: FiltersLayout::AboveContent)
            ->recordActions([
               EditAction::make()   ,
            ])

            ->toolbarActions([
               // Tables\Actions\BulkActionGroup::make([
               // Tables\Actions\DeleteBulkAction::make(),
             ]);

    }

    /**
     * Devuelve los relation managers asociados a importes, ayuda técnica y documentos.
     */
    public static function getRelations(): array
    {
        return [
            //
            ImportesPorOrganismoRelationManager::class,
            AyudaRelationManager::class,
            DocumentosRelationManager::class
        ];
    }

    protected function applySearchToTableQuery(Builder $query, string $search): Builder
    {
        //dd($search);
        return $query
            ->where('carretera', 'like', "%{$search}%")
            ->orWhere('Codigo_Plan', 'like', "%{$search}")
            ->orWhere('ao_ejecucion', 'like', "%{$search}")
            ->orWhere('expediente_id','like',"%{$search}")  // Busca en el campo "carretera"
            ->orWhereHas('municipios', function (Builder $query) use ($search) {
                $query->where('nombre_municipio', 'like', "%{$search}%");
            }) // Busca en el campo "nombre" de la relación "municipio"*/
            ;
    }
    /**
     * @return array{0: array<string, mixed>, 1: array{stage: string|null, master: array<string, mixed>, rows: array<int, array<string, mixed>>}}
     */
    public static function extractImportesManagementStateFromData(array $data): array
    {
        $nestedState = is_array($data['importes_management'] ?? null) ? $data['importes_management'] : [];

        $state = [
            'stage' => isset($nestedState['stage'])
                ? (string) $nestedState['stage']
                : (isset($data['stage']) ? (string) $data['stage'] : null),
            'master' => is_array($nestedState['master'] ?? null)
                ? $nestedState['master']
                : (is_array($data['master'] ?? null) ? $data['master'] : []),
            'rows' => is_array($nestedState['rows'] ?? null)
                ? $nestedState['rows']
                : (is_array($data['rows'] ?? null) ? $data['rows'] : []),
        ];

        unset($data['importes_management'], $data['stage'], $data['master'], $data['rows']);

        return [$data, $state];
    }

    public static function validateImportesManagementState(array $state): void
    {
        $rows = is_array($state['rows'] ?? null) ? $state['rows'] : [];

        if ($rows === []) {
            return;
        }

        $errors = ImportesManagement::percentageErrors($rows);

        if ($errors === []) {
            return;
        }

        throw ValidationException::withMessages([
            'rows' => implode(' · ', array_values($errors)),
        ]);
    }

    public static function persistImportesManagementState(?string $expedienteId, array $state): void
    {
        if (blank($expedienteId)) {
            return;
        }

        $master = is_array($state['master'] ?? null) ? $state['master'] : [];
        $rows = is_array($state['rows'] ?? null) ? $state['rows'] : [];

        if ($master === [] && $rows === []) {
            return;
        }

        app(ImportesManagementRepository::class)->save((string) $expedienteId, $master, $rows);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDatosDeInicioDeObras::route('/'),
            'create' => CreateDatosDeInicioDeObra::route('/create'),
            'edit' => EditDatosDeInicioDeObra::route('/{record}/edit'),
            'view' => ViewInicioDeObra::route('/{record}'),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener el modelo actual
        $record = $this->getRecord();
        //dd('mutateFormDataBeforeFill ejecutado', $data);
        if ($record && $record->ayuda) {
            // Carga los datos de "ayuda" en el formulario
            $data['ayudaTecnica'] = [
                'Expediente' => $record->ayuda->expediente_id,
                'dpto_redactor' => $record->ayuda->dpto_redactor,
                'departamento_direccion' => $record->ayuda->departamento_direccion,
                'AyuTecRed' => $record->ayuda->AyuTecRed,
                'AyuTecDir' => $record->ayuda->AyuTecDir,
                'SubvencionEconomicaR' => $record->ayuda->SubvencionEconomicaR,
                'SubvencionEconomicaD' => $record->ayuda->SubvencionEconomicaD,
                'codigo_municipio' => $record->ayuda->codigo_municipio,
            ];
        }
        //dd($record);
        // Calcular el valor de `ubicacion`
        if ($record) {
            $data['Ubicacion'] = $record->municipios ? $record->municipios->nombre_municipio : $record->carretera;
            $data['Plan'] = $record->planes ? $record->planes->denominacion_plan: null;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si necesitas manipular los datos antes de guardarlos, hazlo aquí
        $data['peticion_ayuda_tec']= $data['ayuda.AyuTecRed'] ==='SI' || $data['AyuTecDir']==='SI' ? 'SI':'NO';
        //dd($data);
        if (isset($data['ayudaTecnica']) && is_array($data['ayudaTecnica'])) {
            foreach ($data['ayuda'] as $key => $value) {
                if (is_array($value)) {
                    $data['ayuda'][$key] = implode(', ', $value);
                }
            }
        }
        return $data;
    }

}

