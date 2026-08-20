<?php

namespace App\Filament\Widgets;

use App\Events\SystemEventOccurred;
use App\Helpers\GetDatosGenerales;
use App\Models\DocumentoGenerico;
use App\Models\PlazoObraActivo;
use App\Services\Prorrogas\ProrrogaRulesService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Livewire;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use App\Models\DocumentoExpediente;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Filament\Facades\Filament;

/**
 * Widget principal de documentos del expediente.
 *
 * Además del alta y edición de metadatos, incorpora un visor PDF integrado
 * que permite abrir documentos almacenados mediante una ruta local del servidor
 * o mediante una URL externa informada en el campo archivo.
 */
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

                // Indica de forma visual si el registro dispone de un PDF enlazado.
                TextColumn::make('archivo')
                    ->label('PDF')
                    ->formatStateUsing(fn ($state, $record) => filled($state ?: ($record->csv ?? null)) ? 'Disponible' : 'Sin archivo')
                    ->badge()
                    ->color(fn ($state, $record) => filled($state ?: ($record->csv ?? null)) ? 'success' : 'gray'),

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
                $this->buildCreateDocumentAction('proyecto'),
                $this->buildCreateDocumentAction('aprobacion'),
                $this->buildCreateDocumentAction('cesion'),
                $this->buildCreateDocumentAction('contratacion'),
                $this->buildCreateDocumentAction('ejecucion'),
                $this->buildCreateDocumentAction('justificacion'),
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

                        TextInput::make('archivo')
                            ->label('Ruta o URL del PDF')
                            ->placeholder('X:\\docs\\expediente_ie\\nombrearchivo.pdf o https://...')
                            ->maxLength(1000)
                            ->helperText('Admite una ruta física del servidor o un enlace web al PDF.'),

                        Livewire::make(\App\Filament\Widgets\DocumentoPdfViewerWidget::class, [
                            'compact' => true,
                        ])
                            ->columnSpanFull(),
                    ]),
                // Acción de consulta con visor embebido del PDF asociado.
                ViewAction::make('Ver Documento')
                    ->label('Ver PDF')
                    ->icon('heroicon-o-folder-open')
                    ->modalWidth('7xl')
                    ->modalHeading(fn ($record) => "Documento {$record->descripcion} del expediente {$record->expediente_id}")
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    /*
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
                    */
                    ->modalContent(fn ($record) => view('filament.widgets.documento-detalle-modal', [
                        'record' => $record,
                    ]))
            ])
            ->emptyStateHeading($this->expedienteSeleccionado ? 'No hay documentos' : 'Selecciona un expediente')
            ->emptyStateDescription($this->expedienteSeleccionado ? 'Agrega el primer documento' : 'Haz click en un expediente de la tabla superior')
            ->emptyStateIcon('heroicon-o-document');
    }

    private function buildCreateDocumentAction(string $phase): Action
    {
        $phaseLabel = DocumentoGenerico::phaseLabel($phase);

        return Action::make("subir_documento_{$phase}")
            ->label("Añadir {$phaseLabel}")
            ->icon('heroicon-o-plus')
            ->color('primary')
            ->modalHeading("Añadir documento de {$phaseLabel}")
            ->fillForm(fn (): array => $this->resolveCreateDocumentDefaults($phase))
            ->schema($this->buildCreateDocumentFormSchema($phase))
            ->action(fn (array $data) => $this->handleDocumentCreate($data, $phase))
            ->disabled(fn (): bool => blank($this->expedienteSeleccionado));
    }

    private function buildCreateDocumentFormSchema(string $phase): array
    {
        return [
            Section::make()
                ->schema([
                    Select::make('expediente_id')
                        ->label('Expediente')
                        ->searchable()
                        ->preload()
                        ->default(fn () => $this->expedienteSeleccionado)
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                    TextInput::make('Codigo_Plan')
                        ->label('Código plan')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['Codigo_Plan'] ?? null)
                        ->required(),
                    TextInput::make('plan_denominacion')
                        ->label('Denominación del plan')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['plan_denominacion'] ?? null)
                        ->dehydrated(false),
                    TextInput::make('referencia')
                        ->label('Número obra')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['referencia'] ?? null)
                        ->required()
                        ->numeric(),
                    TextInput::make('subreferencia')
                        ->label('Subreferencia')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['subreferencia'] ?? null)
                        ->numeric(),
                    TextInput::make('ao_ejecucion')
                        ->label('Año ejecución')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['ao_ejecucion'] ?? null)
                        ->required()
                        ->numeric(),
                    TextInput::make('municipio_nombre')
                        ->label('Municipio')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['municipio_nombre'] ?? null)
                        ->dehydrated(false),
                    TextInput::make('estado_nombre')
                        ->label('Estado de la obra')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['estado_nombre'] ?? null)
                        ->dehydrated(false),
                    TextInput::make('forma_ejecucion_nombre')
                        ->label('Forma de ejecución')
                        ->readOnly()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['forma_ejecucion_nombre'] ?? null)
                        ->dehydrated(false),
                    Select::make('cod_documento')
                        ->label('Tipo Documento')
                        ->options(DocumentoGenerico::getOptionsForPhase($phase))
                        ->searchable()
                        ->required()
                        ->preload(),
                    Textarea::make('descripcion')
                        ->label('Descripción'),
                    DatePicker::make('fechaincorporacion')
                        ->label('Fecha Incorporación'),
                    TextInput::make('archivo')
                        ->label('Ruta o URL del PDF')
                        ->placeholder('X:\\docs\\expediente_ie\\nombrearchivo.pdf o https://...')
                        ->maxLength(1000)
                        ->helperText('Admite una ruta física del servidor o un enlace web al PDF.')
                        ->default(null),
                    TextInput::make('csv')
                        ->maxLength(255)
                        ->default(null),
                    TextInput::make('nregistro')
                        ->maxLength(45)
                        ->default(null),
                    TextInput::make('nsecuencia')
                        ->label('Nº secuencia')
                        ->readOnly()
                        ->numeric()
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['nsecuencia'] ?? null),
                    DatePicker::make('fechaHelp'),
                    Select::make('estado')
                        ->relationship('estados', 'nombre')
                        ->required(),
                    Select::make('team_id')
                        ->label('Municipio')
                        ->relationship('team', 'name')
                        ->default(fn () => $this->resolveCreateDocumentDefaults($phase)['team_id'] ?? Filament::getTenant()?->id),
                    Select::make('destino')
                        ->relationship('destinos', 'destino')
                        ->label('Destino'),
                    Select::make('procedencia')
                        ->relationship('procedencias', 'destino')
                        ->label('Procedencia'),
                ])
                ->columns(2),
        ];
    }

    private function handleDocumentCreate(array $data, string $phase): void
    {
        $documentType = DocumentoGenerico::query()->find($data['cod_documento'] ?? null);

        if ($documentType && ! $this->documentTypeMatchesPhase($documentType, $phase)) {
            throw ValidationException::withMessages([
                'cod_documento' => 'El tipo de documento seleccionado no corresponde a la fase ' . DocumentoGenerico::phaseLabel($phase) . '.',
            ]);
        }

        if ($documentType && $this->isJustificationDocument($documentType)) {
            $plazoJustificacion = PlazoObraActivo::query()
                ->where('expediente_id', $this->expedienteSeleccionado)
                ->where('fase', 'justificacion')
                ->where('activo', true)
                ->orderByDesc('id')
                ->first();

            if ($plazoJustificacion) {
                $rulesService = app(ProrrogaRulesService::class);

                if (! $rulesService->allowJustificationUpload($plazoJustificacion, Carbon::now())) {
                    throw ValidationException::withMessages([
                        'cod_documento' => 'No se puede subir documentación de justificación fuera del plazo vigente. La fecha límite actual es '.Carbon::parse((string) $plazoJustificacion->fecha_fin)->format('d/m/Y').'.',
                    ]);
                }
            }
        }

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
    }

    private function documentTypeMatchesPhase(DocumentoGenerico $documentType, string $phase): bool
    {
        return $documentType->fase_doc === 'ALL'
            || DocumentoGenerico::normalizePhase($documentType->fase_doc) === DocumentoGenerico::normalizePhase($phase);
    }

    /**
     * @return array<string, mixed>
     */
    private function isJustificationDocument(DocumentoGenerico $documentType): bool
    {
        $phase = (string) ($documentType->fase_doc ?? '');

        return str_contains(strtolower($phase), 'justific');
    }

    private function resolveCreateDocumentDefaults(?string $phase = null): array
    {
        $defaults = GetDatosGenerales::getDatosGenerales($this->expedienteSeleccionado);
        $documentDefaults = DocumentoExpediente::applyExpedienteDefaults([
            'expediente_id' => $this->expedienteSeleccionado,
            'team_id' => Filament::getTenant()?->id,
        ]);

        $merged = array_merge($defaults, $documentDefaults);

        return [
            'expediente_id' => $merged['expediente_id'] ?? $this->expedienteSeleccionado,
            'Codigo_Plan' => $merged['Codigo_Plan'] ?? $merged['expediente_codigo_plan'] ?? null,
            'plan_denominacion' => $merged['plan_denominacion'] ?? $merged['expediente_plan_denominacion'] ?? null,
            'referencia' => $merged['referencia'] ?? $merged['numero_obra'] ?? null,
            'numero_obra' => $merged['numero_obra'] ?? $merged['referencia'] ?? null,
            'subreferencia' => $merged['subreferencia'] ?? null,
            'subreferecnia' => $merged['subreferecnia'] ?? $merged['subreferencia'] ?? null,
            'ao_ejecucion' => $merged['ao_ejecucion'] ?? null,
            'municipio_nombre' => $merged['municipio_nombre'] ?? $merged['expediente_municipio_nombre'] ?? null,
            'estado_nombre' => $merged['estado_nombre'] ?? $merged['expediente_estado_nombre'] ?? null,
            'forma_ejecucion_nombre' => $merged['forma_ejecucion_nombre'] ?? $merged['expediente_forma_ejecucion_nombre'] ?? null,
            'nsecuencia' => $merged['nsecuencia'] ?? null,
            'team_id' => $merged['team_id'] ?? Filament::getTenant()?->id,
            'fechaincorporacion' => now(),
        ];
    }
}
