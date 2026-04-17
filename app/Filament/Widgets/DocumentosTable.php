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
use Filament\Schemas\Components\Section;
use App\Models\DocumentoExpediente;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;

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
                    ->modalWidth('7xl')
                    ->modalHeading(fn ($record) => "Documento {$record->descripcion} del expediente {$record->expediente_id}")
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(function ($record): HtmlString {
                        $descripcion = filled($record->descripcion)
                            ? $record->descripcion
                            : '—';

                        $fechaIncorporacion = filled($record->fechaincorporacion)
                            ? \Illuminate\Support\Carbon::parse($record->fechaincorporacion)->format('d/m/Y')
                            : '—';

                        $fechaHelp = filled($record->fechaHelp)
                            ? \Illuminate\Support\Carbon::parse($record->fechaHelp)->format('d/m/Y')
                            : '—';

                        $html = '
                            <div class="space-y-6 text-sm">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div><div class="font-semibold text-gray-600">ID Documento</div><div>' . e($record->idDocumento ?? '—') . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Código Documento</div><div>' . e($record->cod_documento ?? '—') . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Tipo Documento</div><div>' . e(data_get($record, 'tipodocumentos.nombre', '—')) . '</div></div>
                                </div>
                                <div>
                                    <div class="mb-1 font-semibold text-gray-600">Descripción</div>
                                    <div class="p-3 border rounded-lg bg-gray-50">' . $descripcion . '</div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div><div class="font-semibold text-gray-600">Fecha Incorporación</div><div>' . e($fechaIncorporacion) . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Fecha Help</div><div>' . e($fechaHelp) . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Año ejecución</div><div>' . e($record->ao_ejecucion ?? '—') . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Estado</div><div>' . e(data_get($record, 'estados.nombre', $record->estado ?? '—')) . '</div></div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div><div class="font-semibold text-gray-600">Referencia</div><div>' . e($record->referencia ?? '—') . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Subreferencia</div><div>' . e($record->subreferencia ?? '—') . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Nº Registro</div><div>' . e($record->nregistro ?? '—') . '</div></div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div><div class="font-semibold text-gray-600">CSV</div><div>' . e($record->csv ?? '—') . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Notificado</div><div>' . ($record->notificado ? 'Sí' : 'No') . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Expediente</div><div>' . e($record->expediente_id ?? '—') . '</div></div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div><div class="font-semibold text-gray-600">Destino</div><div>' . e(data_get($record, 'destinos.destino', '—')) . '</div></div>
                                    <div><div class="font-semibold text-gray-600">Procedencia</div><div>' . e(data_get($record, 'procedencias.destino', '—')) . '</div></div>
                                </div>
                            </div>';

                        return new HtmlString($html);
                    })
            ])
            ->emptyStateHeading($this->expedienteSeleccionado ? 'No hay documentos' : 'Selecciona un expediente')
            ->emptyStateDescription($this->expedienteSeleccionado ? 'Agrega el primer documento' : 'Haz click en un expediente de la tabla superior')
            ->emptyStateIcon('heroicon-o-document');
    }
}
