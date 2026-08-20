<?php

namespace App\Filament\Shared\Resources\Expedientes;

use App\Events\SystemEventOccurred;
use App\Filament\Obras\Resources\Concerns\HasAssignedExpedienteVisibility;
use App\Filament\Shared\Resources\Expedientes\RelationManagers\DocumentosRelationManager;
use App\Filament\Traits\CommonFilters;
use App\Forms\Components\PlazosObraInfo;
use App\Models\DestinoDeDocumentos;
use App\Models\DocumentoGenerico;
use App\Models\Expediente;
use App\Models\DocumentoExpediente;
use App\Models\Planes;
use App\Models\PlazoObraActivo;
use App\Models\User;
use App\Services\Prorrogas\ProrrogaRulesService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

abstract class ExpedienteResourceBase extends Resource
{
    use HasAssignedExpedienteVisibility;
    use CommonFilters;

    protected static ?string $model = Expediente::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(5)
            ->components([
                TextInput::make('expediente_id')
                    ->disabled()
                    ->required()
                    ->maxLength(510),
                TextInput::make('Codigo_Plan')
                    ->label('Código del plan')
                    ->disabled()
                    ->required()
                    ->maxLength(510),
                TextInput::make('referencia')
                    ->disabled()
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->disabled()
                    ->required()
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->disabled()
                    ->required()
                    ->numeric(),
               TextInput::make('nombre_obra')
                    ->disabled()
                    ->required()
                    ->maxLength(510),
               Select::make('cod_estado')
                    ->relationship('estados', 'estado')
                    ->disabled()
                    ->required(),

                Select::make('cod_estado_help')
                    ->label('Estado en Help')
                    ->relationship('estados', 'estado')
                    ->disabled()
                    ->required(),

                TextInput::make('ejecucion.DEN_CONTRATA')
                    ->label('Forma de Ejecución')
                    ->disabled()
                    ->maxLength(6),
                Select::make('team_id')
                    ->disabled()
                    ->label('Municipio')
                    ->relationship('team', 'name'),
                PlazosObraInfo::make('plazos_obra_info')
                    ->label('Plazos activos')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /*ViewColumn::make('documentos')
                    ->label('docs')
                    ->view('expediente-documentos'),*/

                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('planes.denominacioin_plan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('referencia')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nombre_obra')
                    ->wrap()
                    ->grow()
                    ->searchable(),
                TextColumn::make('estados.estado')
                    ->sortable()
                    ->searchable()
                    ->grow()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('cod_estado_help')
                    ->searchable(),
                TextColumn::make('ejecucion.DEN_CONTRATA')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                self::assignedExpedientesFilter(),
                ...self::getCommonFilters(),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->deferFilters(false)
            ->recordActions([
                Action::make('subirDocumento')
                    ->label('Subir Documento')
                    ->icon('heroicon-o-paper-clip')
                    ->color('success')
                    ->fillForm(function ($record): array {
                        $codigoPlan = trim((string) ($record?->codigo_plan ?? $record?->Codigo_Plan ?? ''));

                        $nombrePlan = '';
                        if ($codigoPlan !== '') {
                            $nombrePlan = (string) (Planes::query()
                                ->where('Codigo_plan', $codigoPlan)
                                ->value('denominacion_plan') ?? '');
                        }

                        return [
                            'expediente_id' => $record?->expediente_id,
                            'Codigo_Plan' => $codigoPlan,
                            'codigo_plan_info' => $codigoPlan,
                            'plan_nombre_info' => $nombrePlan,
                            'nombre_obra_info' => $record?->nombre_obra,
                            'numero_obra' => $record?->referencia,
                            'subreferencia' => $record?->subreferencia,
                            'ao_ejecucion' => $record?->ao_ejecucion,
                            'nsecuencia' => ((int) ($record?->documentos()?->max('nsecuencia') ?? 0)) + 1,
                            'estado' => 'Nuevo',
                            'fechaincorporacion' => now(),
                            'notificado' => false,
                        ];
                    })
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                            'xl' => 3,
                        ])
                            ->schema([
                                TextInput::make('expediente_id')
                                    ->label('Expediente')
                                    ->readOnly(),

                                TextInput::make('descripcion')
                                    ->label('Descripción del Documento')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Select::make('cod_documento')
                                    ->label('Tipo de Documento')
                                    ->options(
                                        DocumentoGenerico::query()
                                            ->orderBy('nombre')
                                            ->pluck('nombre', 'id')
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set): void {
                                        $documentType = DocumentoGenerico::query()->find($state);

                                        if ($documentType) {
                                            $set('destino', $documentType->cod_destino ?: null);
                                            $set('procedencia', $documentType->cod_origen ?: null);
                                        }
                                    })
                                    ->required(),

                                Select::make('estado')
                                    ->label('Estado')
                                    ->options([
                                        'Nuevo' => 'Nuevo',
                                        'Pendiente' => 'Pendiente',
                                        'Validado' => 'Validado',
                                    ])
                                    ->default('Nuevo')
                                    ->required(),

                                Select::make('destino')
                                    ->label('Destino')
                                    ->options(fn (): array => DestinoDeDocumentos::query()
                                        ->orderBy('destino')
                                        ->pluck('destino', 'id')
                                        ->toArray())
                                    ->searchable()
                                    ->preload(),

                                Select::make('procedencia')
                                    ->label('Procedencia')
                                    ->options(fn (): array => DestinoDeDocumentos::query()
                                        ->orderBy('destino')
                                        ->pluck('destino', 'id')
                                        ->toArray())
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('nregistro')
                                    ->label('Nº Registro')
                                    ->maxLength(45),

                                DatePicker::make('fechaHelp')
                                    ->label('Fecha Help'),

                                TextInput::make('remitidopor')
                                    ->label('Remitido por')
                                    ->maxLength(255),

                                Checkbox::make('notificado')
                                    ->label('Notificado')
                                    ->default(false),

                                TextInput::make('csv')
                                    ->label('Ruta o URL del PDF')
                                    ->placeholder('X:\\docs\\expediente_ie\\nombrearchivo.pdf o https://...')
                                    ->maxLength(1000),

                                TextInput::make('Codigo_Plan')
                                    ->label('Código plan')
                                    ->readOnly(),
                                TextInput::make('codigo_plan_info')
                                    ->label('Código plan (info)')
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('plan_nombre_info')
                                    ->label('Plan')
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('nombre_obra_info')
                                    ->label('Nombre obra')
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('numero_obra')
                                    ->label('Número obra')
                                    ->readOnly(),
                                TextInput::make('subreferencia')
                                    ->label('Subreferencia')
                                    ->readOnly(),
                                TextInput::make('ao_ejecucion')
                                    ->label('Año ejecución')
                                    ->readOnly(),
                                TextInput::make('nsecuencia')
                                    ->label('Nº secuencia')
                                    ->readOnly(),

                                DatePicker::make('fechaincorporacion')
                                    ->label('Fecha de Incorporación')
                                    ->default(now())
                                    ->required(),

                                FileUpload::make('archivo')
                                    ->label('Archivo PDF')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(10240)
                                    ->directory('documentos-expedientes')
                                    ->preserveFilenames()
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->action(function (array $data, $record) {
                        $documentType = DocumentoGenerico::query()->find($data['cod_documento']);

                        if ($documentType && self::isJustificationDocumentStatic($documentType)) {
                            $plazoJustificacion = PlazoObraActivo::query()
                                ->where('expediente_id', $record->expediente_id)
                                ->where('fase', 'justificacion')
                                ->where('activo', true)
                                ->orderByDesc('id')
                                ->first();

                            if ($plazoJustificacion) {
                                $rulesService = app(ProrrogaRulesService::class);
                                $uploadDate = Carbon::now();

                                if (! $rulesService->allowJustificationUpload($plazoJustificacion, $uploadDate)) {
                                    throw ValidationException::withMessages([
                                        'cod_documento' => 'No se puede subir documentación de justificación fuera del plazo vigente. La fecha límite actual es '.Carbon::parse((string) $plazoJustificacion->fecha_fin)->format('d/m/Y').'.',
                                    ]);
                                }
                            }
                        }

                        $nextSecuencia = ((int) (DocumentoExpediente::query()
                            ->where('expediente_id', $record->expediente_id)
                            ->max('nsecuencia') ?? 0)) + 1;

                        $expedienteId = $data['expediente_id'] ?? $record->expediente_id;
                        $codigoPlan = $data['Codigo_Plan'] ?? $data['codigo_plan_info'] ?? $record->codigo_plan ?? $record->Codigo_Plan ?? null;
                        $numeroObra = $data['numero_obra'] ?? $record->referencia ?? null;
                        $subreferencia = $data['subreferencia'] ?? $record->subreferencia ?? null;
                        $aoEjecucion = $data['ao_ejecucion'] ?? $record->ao_ejecucion ?? null;

                        $payload = [
                            'expediente_id' => $expedienteId,
                            'Codigo_Plan' => $codigoPlan,
                            'referencia' => $numeroObra,
                            'subreferencia' => $subreferencia,
                            'ao_ejecucion' => $aoEjecucion,
                            'descripcion' => $data['descripcion'],
                            'cod_documento' => $data['cod_documento'],
                            'fechaincorporacion' => $data['fechaincorporacion'] ?? now(),
                            'fechaHelp' => $data['fechaHelp'] ?? null,
                            'nregistro' => $data['nregistro'] ?? null,
                            'nsecuencia' => $nextSecuencia,
                            'csv' => $data['csv'] ?? $data['archivo'] ?? null,
                            'estado' => $data['estado'] ?? 'Nuevo',
                            'destino' => $data['destino'] ?? null,
                            'procedencia' => $data['procedencia'] ?? null,
                            'remitidopor' => $data['remitidopor'] ?? null,
                            'notificado' => (bool) ($data['notificado'] ?? false),
                            'team_id' => $record->team_id,
                        ];

                        $payload = DocumentoExpediente::applyExpedienteDefaults($payload);

                        // Compatibilidad con ambas convenciones en el esquema heredado.
                        $payload['cod_plan'] = $codigoPlan;
                        $payload['Codigo_Plan'] = $codigoPlan;

                        $documento = $record->documentos()->create($payload);

                        event(new SystemEventOccurred(
                            eventType: 'documento_subido',
                            title: 'Nuevo documento incorporado',
                            message: "Se ha subido el documento {$documento->descripcion} al expediente {$record->expediente_id}",
                            type: 'info',
                            toAllUsers: false,
                            userIds: [],
                            teamId: $record->team_id,
                            entity: $documento,
                            meta: [
                                'expediente_id' => $record->expediente_id,
                                'documento_id' => $documento->idDocumento,
                            ],
                        ));

                        Notification::make()
                            ->title('Documento subido correctamente')
                            ->success()
                            ->send();
                    }),

                Action::make('ver_documentos')
                    ->label('Documentos')
                    ->icon('heroicon-o-folder-open')
                    ->size('sm')
                    ->slideOver()
                    ->modalWidth('xl')
                    ->modalHeading(fn ($record) => "Documentos del expediente {$record->expediente_id}")
                    ->modalContent(fn ($record) => view('documentos-slideover', [
                        'expediente' => $record,
                        'documentos' => $record->documentos,
                    ])),
            ])
            ->defaultSort('ao_ejecucion', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withCount('documentos')
                ->with('documentos'))
                        ->recordUrl(fn ($record): string => static::getUrl('view', ['record' => $record]))
            ->toolbarActions([
                  BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function isJustificationDocumentStatic(DocumentoGenerico $documentType): bool
    {
        $phase = (string) ($documentType->fase_doc ?? '');

        return str_contains(strtolower($phase), 'justific');
    }

    public static function getRelations(): array
    {
        $relations = [
            DocumentosRelationManager::class,
            \App\Filament\Ayuntamientos\Resources\Expedientes\RelationManagers\SolicitudesProrrogaRelationManager::class,
        ];

        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos') {
            $relations[] = \App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers\ImportesPorOrganismoRelationManager::class;
        }

        return $relations;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with('documentos');
        $tenant = Filament::getTenant();

        if ($tenant instanceof Model && filled($tenant->getKey())) {
            $query->where('team_id', $tenant->getKey());
        }

        return $query;
    }

    public static function scopeEloquentQueryToTenant(Builder $query, ?Model $tenant): Builder
    {
        if ($tenant instanceof Model && filled($tenant->getKey())) {
            return $query->where('team_id', $tenant->getKey());
        }

        return $query;
    }
}
