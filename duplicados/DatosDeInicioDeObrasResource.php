<?php

namespace App\Filament\Obras\Resources;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\ListDatosDeInicioDeObras;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\CreateDatosDeInicioDeObra;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\EditDatosDeInicioDeObra;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\Pages\ViewInicioDeObra;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\Pages;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\ImportesPorOrganismoRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\AyudaRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\DocumentosRelationManager;
use App\Filament\Traits\ZonasFilter;
use App\Forms\Components\ImportesInfo;
use App\Models\DatosDeInicioDeObras;
use App\Models\TablaDeDepartamento;
use App\Models\TablaDeMunicipio;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource as BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOffice\PhpWord\TemplateProcessor;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Forms\Components\ObraGeneralInfo;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Traits\CommonFilters;
use App\Filament\Traits\MunicipiosFilter;

//use Filament\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;

class DatosDeInicioDeObrasResource extends BaseResource
{
    use MunicipiosFilter;
    use CommonFilters;
    use ZonasFilter;
    protected static ?string $model = DatosDeInicioDeObras::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?int $navigationSort = 1;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationLabel ='Inicio de Obras';**/
    protected static array $searchableAttributes = [
        'carretera',
        'municipios.nombre',
        'Codigo_Plan',
        'ao_ejecuciion',
        'expediente_id',
    ];

    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->year - 10;


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
    public static function form(Schema $schema): Schema
    {
    $record=$schema->getRecord();
    //dd($record->planes->denominacion_plan ?? 'sin plan');
        return $schema
            ->components([
                //
            ObraGeneralInfo::make('informacion_general')
                ->label('Información General de la Obra')
                ->SetObraData($record ?? null),
            ImportesInfo::make('importes')
                ->label('Importes de la Obra')
                ->setImportesDataOrganismo(function($record){
                    dd($record->ImportesporOrganismo);
                }),
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
                ->columns(7)
                ->schema([
                Select::make('TipoActuacion')
                    ->label('Tipo de Actuación')
                    ->relationship('tipoactuacion', 'descripcion_actuacion')
                    ->columnSpan(2)
                    //->dehydrated(false)

                    ->extraAttributes(['class' => 'compact-select']),
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
                        ->columns(3)
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
                        Action::make('Imprimir Notificación')
                            ->icon('heroicon-m-envelope')
                            ->visible(fn ($get) => !empty($get('fecha_pet_acta_replanteo')))
                            ->action(function ($record, $get)
                            {
                                // 1. Obtener datos del procedimiento almacenado
                                //dd($record);
                                if (empty($get('fecha_pet_acta_replanteo'))) {
                                    Notification::make()
                                        ->title('Error de validación')
                                        ->body('Debe completar la fecha de notificación primero')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                                $datos =  DB::select('EXECUTE dbo.PA_R_DatosListados @Plan=?,@Num=?,@SubRef=?,@AoEje=?', [(string)$record->Codigo_Plan, $record->numero_obra,$record->subreferencia,$record->ao_ejecucion])[0];

                                //dd($datos);
                                // 2. Procesar plantilla Word
                                $templatePath = storage_path('app\Modelos_Doc\Petición Ayuda Técnica.dotx');

                                $template = new TemplateProcessor($templatePath);

                                // Reemplazar variables
                                $template->setValue('WD_DEFIPLAN', $datos->denominacion_plan);
                                $template->setValue('WD_LOCALIDAD', $datos->nombre_municipio);
                                $template->setValue('WD_IMPORTEAPROBADO', $datos->importe_aprobado);
                                $template->setValue('WD_FORMAEJE', $datos->DEN_CONTRATA);

                                // 3. Guardar Word temporal
                                $tempWord = tempnam('C:\\', 'word_') . '.docx';

                                $template->saveAs($tempWord);

                                // 4. Convertir a PDF usando LibreOffice

                                $tempPdf = tempnam('C:\\', 'pdf_') . '.pdf';

                                $command = '"C:\Program Files\LibreOffice\program\soffice.exe" --headless --convert-to pdf --outdir ' . dirname($tempPdf) . " " . $tempWord;
                               // dd($command);
                               $pdfFile = str_replace('.docx', '.pdf', $tempWord);
                                shell_exec($command);
                                //dd($tempPdf);
                                // 5. Descargar y limpiar
                                return response()->download($pdfFile, 'documento_final.pdf')
                                    ->deleteFileAfterSend(true);
                            })
                            ->requiresConfirmation())
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
                ->columns(4)
                ->collapsible()
                ->id('ayuda_tecnica')
                ->relationship('ayudaTecnica')
                ->schema([
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->dehydrateStateUsing(function ($state, $get) {

                       dd($state);
                        return empty($state) ? $get('expediente_id') : $state;
                    })
                    ->disabled(),

                Select::make('dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayudaR', 'DENOMINACION')
                    ->extraInputAttributes(['class' => '!h-9 !py-1 !text-sm !leading-none']),

                Select::make('departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayudaD', 'DENOMINACION'),

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
                //
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
        dd('mutateFormDataBeforeFill ejecutado', $data);
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
        dd($data);
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

