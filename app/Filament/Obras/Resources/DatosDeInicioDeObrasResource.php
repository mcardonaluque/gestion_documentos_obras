<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\Pages;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\ImportesPorOrganismoRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\AyudaRelationManager;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\DocumentosRelationManager;
use App\Models\DatosDeInicioDeObras;
use App\Models\TablaDeDepartamento;
use App\Models\TablaDeMunicipio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class DatosDeInicioDeObrasResource extends Resource
{
    protected static ?string $model = DatosDeInicioDeObras::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static array $searchableAttributes = [
        'carretera',
        'municipios.nombre',
        'Codigo_Plan',
        'ao_ejecuciion',
    ];
    public static function getEloquentQuery(): Builder
{
    $añoActual = now()->year;
    
    $añoAnterior2 = now()->subYear(2)->year;
    //dd($añoAnterior2);
    
        return parent::getEloquentQuery()
        ->select('DatosInicioDeObras.*') // Selecciona todas las columnas de la tabla "obras"
            ->leftJoin('TablaDeMunicipios', 'DatosInicioDeObras.municipio', '=', 'codigo_municipio') // Join con la tabla "municipios"
            ->addSelect('TablaDeMunicipios.nombre_municipio')  //->with('municipios');
            ->where('ao_ejecucion', '>=', $añoAnterior2)
            ->where('ao_ejecucion', '<=', $añoActual);
}
    public static function form(Form $form): Form
    {
        //dd($form->getRecord());
        return $form
            ->schema([
                //
            Forms\Components\Select::make('municipio')
                ->label('Municipio')
                ->relationship('municipios', 'nombre_municipio')
                ->disabled()
                //->hidden()
                ->extraAttributes(['class' => 'custom-select-class'])
                ->visible(fn ($get) => $get('municipio'))
                ->reactive()    , // Hace que el campo sea reactivo

            // Campo para carretera
            Forms\Components\TextInput::make('carretera')
                ->label('Carretera')
                ->disabled()
                //->hidden()
                ->visible(fn ($get) => $get('carretera'))
                ->reactive(), // Hace que el campo sea reactivo
          
            Forms\Components\Section::make('Referencia de Obra')
                ->columns(2)
                ->schema([
            Forms\Components\TextInput::make('Codigo_Plan')
                    ->label('Plan')
                    //->required()
                    ->hidden()
                    ->disabled(),
            Forms\Components\TextInput::make('numero_obra')
                    ->label('número de obra')
                    //->required()
                    ->hidden()
                    ->disabled(),
            Forms\Components\TextInput::make('subreferencia')
                    //->required()
                    ->hidden()
                    ->disabled(),    
            Forms\Components\TextInput::make('ao_ejecucion')
                    ->label('año de ejecución')
                    ->hidden()
                    //->required()
                    ->disabled(),
            Forms\Components\PlaceHolder::make('Obra')
                    ->label('Obra')
                    ->content(function ($get, $record) {
                        return $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
                    })
                    ->disabled() // Hace que el campo sea de solo lectura
                    ->dehydrated(false),

            Forms\Components\Select::make('Plan')
                    ->label('Plan')
                    ->relationship('planes', 'denominacion_plan')
                    ->dehydrated(false)
                    ->disabled()
                    ->extraAttributes(['class' => 'custom-select-class']),
                                        
                ]),
            Forms\Components\Section::make('Datos de la Obra')
                    ->columns(5)
                    ->schema([
                          // Campo virtual "Ubicación"
            Forms\Components\TextInput::make('Ubicacion')
                    ->label('Ubicación')
                    ->disabled() // Hace que el campo sea de solo lectura
                    ->dehydrated(false) // Evita que el campo se guarde en la base de datos
                    ->visible(fn ($get) => $get('municipio') || $get('carretera')) // Solo visible si hay un municipio o carretera
                    ,
            Forms\Components\PlaceHolder::make('zona')
                    ->label('Zona')
                    ->content(function ($get, $record) {
                        // Obtener el municipio y su zona
                        $municipio = $record?->municipios;
                        if ($municipio && $municipio->zonas) {
                            return $municipio->zonas->ZONA; // Asegúrate de que `ZONA` sea el nombre correcto del campo
                        }
                        return 'No disponible';
                    })
                    ->dehydrated(false)
                    ->disabled(),
            Forms\Components\TextInput::make('nombre_obra1')
                    ->label('Nombre')
                    ->columnSpan(2)
                    ->required()
                    ->disabled(),
                
            Forms\Components\TextInput::make('forma_ejecucion')
                    ->label('Forma ejecución')
                    ->columnSpan(2)
                    //->required()
                    ->disabled(),
            Forms\Components\PlaceHolder::make('ejecucion')
                    ->label('Forma de Ejecución')
                    ->content(function ($get, $record) {
                        // Obtener el municipio y su zona
                        $ejecucion = $record?->ejecucion;
                        if ($ejecucion ) {
                            return $ejecucion->DEN_CONTRATA; // Asegúrate de que `ZONA` sea el nombre correcto del campo
                        }
                        return 'No disponible';
                    })
                    ->dehydrated(false)
                    ->disabled(),
                
                ]),   
            Forms\Components\Section::make('Referencia de Obra')
                ->columns(7)
                ->schema([
                Forms\Components\Select::make('TipoActuacion')
                    ->label('Tipo de Actuación')
                    ->relationship('tipoactuacion', 'descripcion_actuacion')
                    //->dehydrated(false)
                   
                    ->extraAttributes(['class' => 'custom-select-class']),
                Forms\Components\TextInput::make('TipoObra')
                    ->nullable()
                    ->label('Tipo de Obra'),
                    //->required()
                    
                Forms\Components\TextInput::make('LicenciaObra')
                    ->label('Licencia de Obra')
                    ->maxlength(2)
                    ->extraAttributes(['class' => 'custom-textinput-class']),
                Forms\Components\TextInput::make('disponibilidad_terreno')
                    ->label('Disp. Terrenos')
                    ->nullable()
                    ->extraAttributes(['class' => 'custom-textinput-class']),
                    //->required()
                Forms\Components\TextInput::make('Expediente')
                    ->label('Expediente')
                    ->required(),
                Forms\Components\TextInput::make('PartidaPresupuesto')
                    ->nullable()
                    ->label('Partida Presupuestaria'),
                Forms\Components\TextArea::make('EstadoServicioTecnico')
                    ->label('Estado Servicio Tecnico')
                    ->nullable()
                    ->extraAttributes(['class' => 'custom-textinput-class']),

                Forms\Components\TextArea::make('EstadoServicioAdministrativo')
                    ->label('Estado Servicio Admnistrativo')
                    ->nullable()
                    ->extraAttributes(['class' => 'custom-textinput-class']),
                Forms\Components\TextArea::make('comentario')
                    ->label('Comentario')
                    ->nullable()
                    ->extraAttributes(['class' => 'custom-textinput-class']),
                Forms\Components\Select::make('TipoPrograma')
                    ->label('Tipo Programa')
                    ->options([
                        'Concertado' => 'Concertado',
                        'No-Concertado ' => 'NO_Concertado',
                        
                    ])
                    ->extraAttributes(['class' => 'custom-select-class']),
                Forms\Components\radio::make('Aportacion')
                    ->label('Aportación del Ayto.')
                    ->reactive()
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($state, callable $set){
                       // dd($state);
                        $set('CompApAyto',  $state == 'SI' ? 'visible' : 'hidden');
                    })
                    ->options([
                        'SI' => 'SI',
                        'NO' => 'NO',
                    ]),
                
                Forms\Components\radio::make('CompApAyto')
                        ->label('Forma de Aportación')
                        ->reactive()
                        ->visible(fn ($get) => $get('Aportacion')==='SI')
                        ->dehydrateStateUsing(function ($state, $get) {
                            // Si el campo "forma" está vacío, guarda el valor del campo radio
                          
                            return empty($state) ? $get('Aportacion') : $state;
                        })
                        ->afterStateUpdated(function ($state, callable $set){
                            // dd($state);
                            $set('Patronato',  $state == 'RE' ? 'visible' : 'hidden');
                            $set('FechaComPatronato',  $state == 'RE' ? 'visible' : 'hidden');
                            $set('FechaIngresoAyto',  $state == 'RE' ? 'visible' : 'hidden');
                            $set('Aceptacion',  $state == 'RE' ? 'visible' : 'hidden');
                    
                         })
                        ->options([
                            'RE' => 'Retraer de Recaudación',
                            'IN' => 'Ingreso del Ayto.',
                            'EF' => 'Entidad financiera',
    
                    ]),
                    Forms\Components\Select::make('Clasificacion')
                    ->label('Clasificación.')
                    ->reactive()
                    ->dehydrated(false)
                    ->options([
                        'SI' => 'SI',
                        'NO' => 'NO',
                        'NR' => 'NR'                    ]),
               
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
               
              
            Forms\Components\Section::make('Ayuda Téncica')
                ->columns(4)
                ->schema([
                Forms\Components\TextInput::make('ayuda.Expediente')
                    ->label('Expediente')
                    ->dehydrateStateUsing(function ($state, $get) {
                       
                       dd($state);
                        return empty($state) ? $get('ayuda.Expediente') : $state;
                    })
                    ->disabled(),

                Forms\Components\Select::make('ayuda.dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayuda.ayudaR', 'DENOMINACION'),
                    
                Forms\Components\Select::make('ayuda.departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayuda.ayudaD', 'DENOMINACION'),

                Forms\Components\TextInput::make('ayuda.AyuTecRed')
                    ->label('Ayuda Técnica a la Redacción'),               

                Forms\Components\TextInput::make('ayuda.AyuTecDir')
                ->label('Ayuda Técnica a la Dirección'),               
                    
                Forms\Components\TextInput::make('ayuda.SubvencionEconomicaR')
                    ->label('Subvención en Redacción'),

                Forms\Components\TextInput::make('ayuda.SubvencionEconomicaD')
                    ->label('Subvención en Dirección'),
                Forms\Components\Select::make('ayuda.codigo_municipio')
                    ->label('Municipio')
                    ->relationship('ayuda.municipio','nombre_municipio'),

                ]),
           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->sortable()
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
                //    dd($record->municipio->nombre_municipio ?? $record->carretera);

                    return $record->municipio->nombre_municipio ?? $record->carretera;
                })
                ->searchable(),
                           
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
            DocumentosRelationManager::class
        ];
    }
    protected function applySearchToTableQuery(Builder $query, string $search): Builder
    {
        dd($search);
        return $query
            ->where('carretera', 'like', "%{$search}%")
            ->orWhere('Codigo_Plan', 'like', "%{$search}")
            ->orWhere('ao_ejecucion', 'like', "%{$search}")  // Busca en el campo "carretera"
            /*->orWhereHas('municipios', function (Builder $query) use ($search) {
                $query->where('nombre_municipio', 'like', "%{$search}%"); // Busca en el campo "nombre" de la relación "municipio"*/
            ;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatosDeInicioDeObras::route('/'),
            'create' => Pages\CreateDatosDeInicioDeObras::route('/create'),
            'edit' => Pages\EditDatosDeInicioDeObras::route('/{record}/edit'),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener el modelo actual
        $record = $this->getRecord();
        if ($record && $record->ayuda) {
            // Carga los datos de "ayuda" en el formulario
            $data['ayuda'] = [
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
        dd($record);
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
        if (isset($data['ayuda']) && is_array($data['ayuda'])) {
            foreach ($data['ayuda'] as $key => $value) {
                if (is_array($value)) {
                    $data['ayuda'][$key] = implode(', ', $value);
                }
            }
        }
        return $data;
    }
    
}

