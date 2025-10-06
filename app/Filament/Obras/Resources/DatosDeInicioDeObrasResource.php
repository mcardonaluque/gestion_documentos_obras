<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\Pages;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\ImportesPorOrganismoRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\AyudaRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\DocumentoExpedienteRelationManager;
use App\Models\DatosDeInicioDeObras;
use App\Models\TablaDeDepartamento;
use App\Models\TablaDeMunicipio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource as BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions\Action;
use PhpOffice\PhpWord\TemplateProcessor;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Forms\Components\ObraGeneralInfo;
class DatosDeInicioDeObrasResource extends BaseResource
{
    protected static ?string $model = DatosDeInicioDeObras::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationLabel ='Inicio de Obras';**/
    protected static array $searchableAttributes = [
        'carretera',
        'municipios.nombre',
        'Codigo_Plan',
        'ao_ejecuciion',
        'Expediente',
    ];
 
    public static function getEloquentQuery(): Builder
    {
        $añoActual = now()->year;

        $añoAnterior2 = now()->subYear(10)->year;
   

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
    public static function form(Form $form): Form
    {
    $record=$form->getRecord();
    //dd($record->planes->denominacion_plan ?? 'sin plan');
        return $form
            ->schema([
                //
            ObraGeneralInfo::make('informacion_general')
                ->label('Información General de la Obra')
                ->SetObraData($record ?? null),
           /* Forms\Components\Select::make('municipio')
                ->label('Municipio')
                ->relationship('municipios', 'nombre_municipio')
                ->disabled()
                //->hidden()
                ->extraAttributes(['class' => 'custom-select-class'])
                ->visible(fn ($get) => $get('municipio'))
                ->reactive()    , // Hace que el campo sea reactivo
                */
            
            Forms\Components\Section::make('Datos de Obra')
                ->columns(7)
                ->schema([
                Forms\Components\Select::make('TipoActuacion')
                    ->label('Tipo de Actuación')
                    ->relationship('tipoactuacion', 'descripcion_actuacion')
                    ->columnSpan(2)
                    //->dehydrated(false)

                    ->extraAttributes(['class' => 'compact-select']),
                Forms\Components\TextInput::make('TipoObra')
                    ->nullable()
                    ->label('Tipo de Obra'),
                    
                    //->required()

                Forms\Components\Select::make('LicenciaObra')
                    ->label('Licencia de Obra')
                    ->options([
                            'SI' => 'SI',
                            'NO' => 'NO',
                            'NR' => 'NR'                    ]),
                Forms\Components\Select::make('disponibilidad_terreno')
                    ->label('Disp. Terrenos')
                    ->nullable()
                    //->extraAttributes(['class' => 'compact-select'])
                    ->options([
                        'SI' => 'SI',
                        'NO' => 'NO',
                        'NR' => 'NR'                    ]),
                    //->required()
                Forms\Components\TextInput::make('Expediente')
                    ->label('Expediente')
                    ->required(),
                Forms\Components\TextInput::make('PartidaPresupuesto')
                    ->nullable()
                    ->label('Partida Presupuestaria'),
                Forms\Components\TextInput::make('peticion_ayuda_tec')
                    ->nullable()
                    ->label('Petición Ayuda técnica'),
                Forms\Components\TextArea::make('EstadoServicioTecnico')
                    ->label('Estado Servicio Tecnico')
                    ->nullable(),

                Forms\Components\TextArea::make('EstadoServicioAdministrativo')
                    ->label('Estado Servicio Admvo.')
                    ->nullable(),
                Forms\Components\TextArea::make('comentario')
                    ->label('Comentario')
                    ->nullable(),
                Forms\Components\Select::make('TipoPrograma')
                    ->label('Tipo Programa')
                    ->options([
                        'Concertado' => 'Concertado',
                        'No-Concertado ' => 'NO_Concertado',

                    ]),
                Forms\Components\Select::make('Clasificacion')
                    ->label('Clasificación.')
                    ->reactive()
                    ->dehydrated(false)
                    ->options([
                        'SI' => 'SI',
                        'NO' => 'NO',
                        'NR' => 'NR'                    ]),
                Forms\Components\TextInput::make('importes.importe_aprobado')
                            ->label('Importe Aprobado')
                            ->nullable()
                            ->numeric(),
                Forms\Components\radio::make('Aportacion')
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

                Forms\Components\radio::make('CompApAyto')
                        ->label('Forma de Aportación')
                        ->reactive()
                        ->visible(fn ($get) => $get('Aportacion')==='SI')
                        //->dehydrateStateUsing(function ($state, $get) {
                            // Si el campo "forma" está vacío, guarda el valor del campo radio
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
                   

                    Forms\Components\FieldSet::make('Patronato')
                        ->label('Patronato')
                        ->columns(3)
                        ->visible(fn ($get) => $get('CompApAyto') ==='RE')
                            ->schema([
                                Forms\Components\DatePicker::make('FechaComPatronato')
                                    ->label('Fecha Com. Patronato')
                                    ->nullable(),
                                Forms\Components\Select::make('Aceptacion')
                                    ->label('Aceptación')
                                    ->options([
                                        'SI' => 'SI',
                                        'NO ' => 'NO'
                                    ]),
                                Forms\Components\DatePicker::make('FechaIngresoAyto')
                                    ->label('Fecha Ingreso Ayto.')
                                    ->nullable(),

                            ]),
                        
                            

                ]),
                Forms\Components\Section::make('Fechas')
                ->columns(4)
                //->id('ayuda')
                //->relationship('ayuda')
                ->schema([
                    Forms\Components\DatePicker::make('fecha_notificacion_ayto')
                    ->nullable()
                    ->label('Notifif. Ayuntamiento'),
                    Forms\Components\DatePicker::make('fecha_pet_acta_replanteo')
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
                Forms\Components\DatePicker::make('fecha_acta_replanteo_previo')
                    ->nullable()
                    ->label('Acta de Repl. Previo'),
                Forms\Components\DatePicker::make('fecha_rem_pet_ayuda')
                    ->nullable()
                    ->label('Petición de ayuda técnica'),
                Forms\Components\DatePicker::make('fecha_envio_fiscalizacion')
                    ->nullable()
                    ->label('Envio a Fiscalizar'),
                Forms\Components\DatePicker::make('fecha_fiscalizacion')
                    ->nullable()
                    ->label('Fecha Fiscalización'),
                Forms\Components\DatePicker::make('fecha_prev_comienzo_obra')
                    ->nullable()
                    ->label('Prevista Comienzo'),
                Forms\Components\DatePicker::make('fecha_prev_term_obra')
                    ->nullable()
                    ->label('Prevista Terminación'),
                Forms\Components\DatePicker::make('FechaMediosMatAyto')
                    ->nullable()
                    ->label('Medios Materiales Ayto.'),
                ]),

            Forms\Components\Section::make('Ayuda Técnica')
                ->columns(4)
                ->collapsible()
                ->id('ayuda_tecnica')
                ->relationship('ayudaTecnica')
                ->schema([
                Forms\Components\TextInput::make('Expediente')
                    ->label('Expediente')
                    ->dehydrateStateUsing(function ($state, $get) {

                       dd($state);
                        return empty($state) ? $get('Expediente') : $state;
                    })
                    ->disabled(),

                Forms\Components\Select::make('dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayudaR', 'DENOMINACION')
                    ->extraInputAttributes(['class' => '!h-9 !py-1 !text-sm !leading-none']),

                Forms\Components\Select::make('departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayudaD', 'DENOMINACION'),

                Forms\Components\TextInput::make('AyuTecRed')
                    ->label('Ayuda Técnica a la Redacción')
                    ->extraInputAttributes(['class' => 'bg-red']),

                Forms\Components\TextInput::make('AyuTecDir')
                ->label('Ayuda Técnica a la Dirección'),

                Forms\Components\TextInput::make('SubvencionEconomicaR')
                    ->label('Subvención en Redacción'),

                Forms\Components\TextInput::make('SubvencionEconomicaD')
                    ->label('Subvención en Dirección'),
                

                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        
        return $table
        ->defaultSort('ao_ejecucion', 'desc')
            ->columns([
                //

            Tables\Columns\TextColumn:: make('Codigo_Plan')
                ->sortable()
                ->hidden()
                ->searchable(),
            Tables\Columns\TextColumn::make('numero_obra')
                ->sortable()
                ->searchable()
                ->hidden(),
            Tables\Columns\TextColumn::make('subreferencia')
                ->searchable()
                ->hidden(),
            Tables\Columns\TextColumn::make('ao_ejecucion')
                ->sortable()
                ->searchable()
                ->hidden(),
            Tables\Columns\TextColumn::make('Obra')
                    ->label('Obra')
                    // ->sortable()
                   // ->searchable()
                   // ->grow()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->getStateUsing(function ($record) {
                        return $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
                    }),
            Tables\Columns\TextColumn::make('nombre_obra1')
            ->label('Nombre')
            ->limit(50, '... [más]') // Limita a 50 caracteres y usa "... [más]" como indicador de truncamiento
            ->tooltip(function ( Tables\Columns\TextColumn $column): ?string {
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
            Tables\Columns\TextColumn::make('Ubicacion')
                ->label('Ubicación')
                ->getStateUsing(function ($record) {
                 // dd($record->municipios);
                 return ($record->municipio !== null && $record->municipio !== 0)
                 ? $record->municipios->nombre_municipio
                 : $record->carretera;
                    //return $record->municipios->nombre_municipio ?: $record->carretera;
                }),
                //->searchable(),

            Tables\Columns\TextColumn::make('municipios.nombre_municipio')
                ->sortable()
                ->searchable()
                ->hidden()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            Tables\Columns\TextColumn::make('carretera')
                ->sortable()
                ->searchable()
                ->hidden()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
           Tables\Columns\TextColumn::make('estados.estado_abrev')
                ->sortable()
                ->searchable()
                ->grow()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            Tables\Columns\TextColumn::make('forma_ejecucion')
                ->Label('F.Ejecuc.')
                ->sortable()
                ->width(50)
                ->searchable()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            Tables\Columns\TextColumn::make('municipios.zonas.ZONA')
                ->label('Zona')
                ->sortable()
                ->searchable()
                ->grow()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false),
            Tables\Columns\TextColumn::make('Expediente')
                ->label('Expediente')
                ->sortable()
                ->searchable()
                ->grow()
                ->extraHeaderAttributes(['class' => 'px-8'])
                ->extraCellAttributes(['class' => 'px-8'])
                ->toggleable(isToggledHiddenByDefault: false)
            ])

            ->filters([
                //
            ])
            ->actions([
              //  Tables\Actions\EditAction::make()   ,
            ])

            ->bulkActions([
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
            DocumentoExpedienteRelationManager::class
        ];
    }

    protected function applySearchToTableQuery(Builder $query, string $search): Builder
    {
        //dd($search);
        return $query
            ->where('carretera', 'like', "%{$search}%")
            ->orWhere('Codigo_Plan', 'like', "%{$search}")
            ->orWhere('ao_ejecucion', 'like', "%{$search}")
            ->orWhere('Expediente','like',"%{$search}")  // Busca en el campo "carretera"
            ->orWhereHas('municipios', function (Builder $query) use ($search) {
                $query->where('nombre_municipio', 'like', "%{$search}%");
            }) // Busca en el campo "nombre" de la relación "municipio"*/
            ;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatosDeInicioDeObras::route('/'),
            'create' => Pages\CreateDatosDeInicioDeObras::route('/create'),
            'edit' => Pages\EditDatosDeInicioDeObras::route('/{record}/edit'),
            'view' => Pages\ViewInicioDeObras::route('/{record}'),
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
                'Expediente' => $record->ayuda->Expediente,
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

