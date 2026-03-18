<?php

namespace App\Filament\Widgets;

use App\Events\SystemEventOccurred;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Models\DocumentoExpediente;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;
use Filament\Facades\Filament;

class DocumentosTable extends BaseWidget
{
    public ?string $expedienteSeleccionado = null;
    public ?string $documentoSeleccionado = null;
    protected static ?string $heading = 'Documentos del Expediente';
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): string
    {
        if (blank($this->expedienteSeleccionado)) {
            return 'Documentos del Expediente';
        }

        return "Documentos del Expediente: {$this->expedienteSeleccionado}";
    }

    // Escuchar el evento del otro widget
    #[On('expedienteSeleccionado')]
    public function actualizarExpediente($expedienteId): void
    {
        $this->expedienteSeleccionado = $expedienteId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(fn (): string => blank($this->expedienteSeleccionado)
                ? 'Documentos del Expediente'
                : "Documentos del Expediente: {$this->expedienteSeleccionado}")
            ->query(function () {
                if (!$this->expedienteSeleccionado) {
                    return DocumentoExpediente::where('idDocumento','0'); // Query vacío
                }
               //dd(DocumentoExpediente::where('expediente_id', $this->expedienteSeleccionado));
                return DocumentoExpediente::where('expediente_id', $this->expedienteSeleccionado);
            })
            ->columns([
                TextColumn::make('idDocumento')
                    ->label('Id Documento')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tipodocumentos.nombre')
                    ->label('Tipo Documento')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tipodocumentos.fasedoc.nombre')
                    ->label('Fase')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('fechaincorporacion')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completado', 'aprobado' => 'success',
                        'pendiente' => 'warning',
                        'rechazado' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
            CreateAction::make()
                    ->label('Añadir un documento de expediente')
                    ->schema([
                Section::make()
                    ->schema([
                Select::make('expediente_id')
                    ->label('Expediente')
                    ->relationship('expedientes', 'expediente_id')
                    ->searchable()
                    ->preload()
                    ->default(fn () => $this->expedienteSeleccionado)
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('Codigo_Plan')
                    ->relationship('planes', 'codigo_plan')
                    ->label('Código plan')
                    ->default(fn () => DocumentoExpediente::applyExpedienteDefaults([
                        'expediente_id' => $this->expedienteSeleccionado,
                    ])['Codigo_Plan'] ?? null)
                    ->disabled()
                    ->required(),
                TextInput::make('referencia')
                    ->label('Número obra')
                    ->readOnly()
                    ->default(fn () => DocumentoExpediente::applyExpedienteDefaults([
                        'expediente_id' => $this->expedienteSeleccionado,
                    ])['referencia'] ?? null)
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->label('Subreferencia')
                    ->readOnly()
                    ->default(fn () => DocumentoExpediente::applyExpedienteDefaults([
                        'expediente_id' => $this->expedienteSeleccionado,
                    ])['subreferencia'] ?? null)
                    ->numeric()
                    ->default(null),
                TextInput::make('ao_ejecucion')
                    ->label('Año ejecución')
                    ->readOnly()
                    ->default(fn () => DocumentoExpediente::applyExpedienteDefaults([
                        'expediente_id' => $this->expedienteSeleccionado,
                    ])['ao_ejecucion'] ?? null)
                    ->required()
                    ->numeric(),
                Select::make('cod_documento')
                        ->label('Tipo Documento')
                        ->relationship('tipodocumentos', 'nombre')
                        ->searchable()
                        ->required()
                        ->preload(),

                Textarea::make('descripcion')
                        ->label('Descripción'),

                DatePicker::make('fechaincorporacion')
                        ->label('Fecha Incorporación'),

                Select::make('estado')
                        ->label('Estado')
                        ->relationship('estados', 'nombre')
                        ->required(),
                TextInput::make('csv')
                    ->required()
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                TextInput::make('nsecuencia')
                    ->label('Nº secuencia')
                    ->readOnly()
                    ->numeric()
                    ->default(fn () => DocumentoExpediente::applyExpedienteDefaults([
                        'expediente_id' => $this->expedienteSeleccionado,
                    ])['nsecuencia'] ?? null),
                 DatePicker::make('fechaHelp'),
                  Select::make('estado')
                    ->relationship('estados', 'nombre')
                    ->required(),

                Select::make('team_id')
                    ->label('Municipio')
                    ->relationship('team', 'name')
                    ->default(fn () => Filament::getTenant()?->id),
                Select::make('destino')
                    ->relationship('destinos', 'destino')
                    ->label('Destino'),
                Select::make('procedencia')
                    ->relationship('procedencias', 'destino')
                    ->label('Procedencia'),
                    ])
                    ->columns(2),
                    ])

                ->action(function (array $data) {
                        // Asignar automáticamente el expediente seleccionado
                        $data['expediente_id'] = $this->expedienteSeleccionado;
                        $data = DocumentoExpediente::applyExpedienteDefaults($data);
                        $documento = DocumentoExpediente::create($data);

                        event(new SystemEventOccurred(
                            eventType: 'documento_subido_widget',
                            title: 'Documento incorporado al expediente',
                            message: "Se ha incorporado el documento {$documento->descripcion} al expediente {$documento->expediente_id}.",
                            type: 'info',
                            toAllUsers: false,
                            userIds: [],
                            teamId: $documento->team_id,
                            entity: $documento,
                            meta: [
                                'origin' => 'documentos_table_widget',
                                'expediente_id' => $documento->expediente_id,
                                'documento_id' => $documento->idDocumento,
                            ],
                        ));
                    })
                    ->disabled(fn () => !$this->expedienteSeleccionado),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        TextInput::make('cod_documento')
                            ->label('Código Documento')
                            ->required(),

                        Select::make('documento')
                            ->label('Tipo Documento')
                            ->relationship('tipodocumentos', 'nombre')
                            ->searchable()
                            ->preload(),

                        Textarea::make('descripcion')
                            ->label('Descripción'),

                        DatePicker::make('fechaincorporacion')
                            ->label('Fecha Incorporación'),

                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'completado' => 'Completado',
                                'aprobado' => 'Aprobado',
                                'rechazado' => 'Rechazado',
                            ]),
                    ]),
                ViewAction::make('Ver Documento')

                ->label('Ver Documento')
                ->icon('heroicon-o-folder-open')
                ->modalWidth('x1') // opcional: ancho del modal
                ->modalHeading(fn ($record) => "Documento {$record->descripcion} del expediente {$record->expediente_id}")
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Cerrar')
                ->modalContent(function ($record) {
                return Schema::make()
                ->record($record)
                    ->components([
                    Section::make('Información del Documento')
                    ->schema([
                        TextEntry::make('idDocumento')
                            ->label('ID Documento'),
                        TextEntry::make('cod_documento')
                            ->label('Código Documento'),
                        TextEntry::make('tipodocumentos.nombre')
                            ->label('Tipo Documento'),
                    ])
                    ->columns(3),

                Section::make('Contenido')
                    ->schema([
                        TextEntry::make('descripcion')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->html(), // Si contiene HTML
                    ]),

                Section::make('Fechas y Estado')
                    ->schema([
                        TextEntry::make('fechaincorporacion')
                            ->label('Fecha Incorporación')
                            ->date('d/m/Y'),
                        TextEntry::make('fechaHelp')
                            ->label('Fecha Help')
                            ->date('d/m/Y'),
                        TextEntry::make('ao_ejecucion')
                            ->label('Año Ejecución'),
                        TextEntry::make('estados.nombre')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'completado', 'aprobado' => 'success',
                                'pendiente' => 'warning',
                                'rechazado' => 'danger',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),

                Section::make('Referencias')
                    ->schema([
                        TextEntry::make('referencia')
                            ->label('Referencia'),
                        TextEntry::make('subreferencia')
                            ->label('Subreferencia'),
                        TextEntry::make('nregistro')
                            ->label('Nº Registro'),
                       /* \Filament\Infolists\Components\TextEntry::make('nsecuencia')
                            ->label('Nº Secuencia'),*/
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Información Adicional')
                    ->schema([
                        TextEntry::make('csv')
                            ->label('CSV'),
                        TextEntry::make('notificado')
                            ->label('Notificado')
                            ->badge()
                            ->color(fn ($state): string => $state ? 'success' : 'gray')
                            ->formatStateUsing(fn ($state): string => $state ? 'Sí' : 'No'),

                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('Relaciones')
                    ->schema([
                        TextEntry::make('expediente_id')
                            ->label('Expediente'),
                        TextEntry::make('destinos.destino')
                            ->label('Destino'),
                        TextEntry::make('procedencias.destino')
                            ->label('Procedencia'),
                    ])
                    ->columns(3)
                    ->collapsible(),
                    ]);
        })
            ])
            ->emptyStateHeading($this->expedienteSeleccionado ? 'No hay documentos' : 'Selecciona un expediente')
            ->emptyStateDescription($this->expedienteSeleccionado ? 'Agrega el primer documento' : 'Haz click en un expediente de la tabla superior')
            ->emptyStateIcon('heroicon-o-document');
    }
}
