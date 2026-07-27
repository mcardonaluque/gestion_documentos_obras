<?php

namespace App\Filament\Shared\Resources\Expedientes;

use App\Events\SystemEventOccurred;
use App\Filament\Obras\Resources\Concerns\HasAssignedExpedienteVisibility;
use App\Filament\Shared\Resources\Expedientes\RelationManagers\DocumentosRelationManager;
use App\Filament\Traits\CommonFilters;
use App\Forms\Components\PlazosObraInfo;
use App\Models\DocumentoGenerico;
use App\Models\Expediente;
use App\Models\DocumentoExpediente;
use App\Models\Planes;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
                TextInput::make('codigo_plan')
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
                ViewColumn::make('documentos')
                    ->label('docs')
                    ->view('expediente-documentos'),

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
                                ->where('codigo_plan', $codigoPlan)
                                ->value('denominacion_plan') ?? '');
                        }

                        return [
                            'expediente_id' => $record?->expediente_id,
                            'codigo_plan_info' => $codigoPlan,
                            'plan_nombre_info' => $nombrePlan,
                            'nombre_obra_info' => $record?->nombre_obra,
                            'numero_obra' => $record?->referencia,
                            'subreferencia' => $record?->subreferencia,
                            'ao_ejecucion' => $record?->ao_ejecucion,
                            'nsecuencia' => ((int) ($record?->documentos()?->max('nsecuencia') ?? 0)) + 1,
                            'created_at' => now(),
                        ];
                    })
                    ->schema([
                        TextInput::make('expediente_id')
                            ->label('Expediente')
                            ->readOnly(),

                        TextInput::make('codigo_plan_info')
                            ->label('Código plan')
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
                            ->readOnly(),

                        TextInput::make('ao_ejecucion')
                            ->label('Año ejecución')
                            ->readOnly(),

                        TextInput::make('nsecuencia')
                            ->label('Nº secuencia')
                            ->readOnly(),

                        TextInput::make('descripcion')
                            ->label('Descripción del Documento')
                            ->required()
                            ->maxLength(255),

                        Select::make('cod_documento')
                            ->label('Tipo de Documento')
                            ->options(
                                DocumentoGenerico::query()
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id')
                            )
                            ->searchable()
                            ->required(),

                        FileUpload::make('archivo')
                            ->label('Archivo PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->directory('documentos-expedientes')
                            ->preserveFilenames()
                            ->required(),
                        DatePicker::make('created_at')
                            ->label('Fecha de Incorporación')
                            ->disabled()
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {
                        $nextSecuencia = ((int) (DocumentoExpediente::query()
                            ->where('expediente_id', $record->expediente_id)
                            ->max('nsecuencia') ?? 0)) + 1;

                        $payload = [
                            'expediente_id' => $record->expediente_id,
                            'referencia' => $record->referencia,
                            'subreferencia' => $record->subreferencia,
                            'ao_ejecucion' => $record->ao_ejecucion,
                            'descripcion' => $data['descripcion'],
                            'cod_documento' => $data['cod_documento'],
                            'fechaincorporacion' => $data['created_at'],
                            'nsecuencia' => $nextSecuencia,
                            'csv' => $data['archivo'],
                            'estado' => 'Nuevo',
                            'team_id' => $record->team_id,
                        ];

                        $payload = DocumentoExpediente::applyExpedienteDefaults($payload);

                        // Compatibilidad con ambas convenciones en el esquema heredado.
                        $codigoPlan = $record->codigo_plan ?? $record->Codigo_Plan ?? null;
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

    public static function getRelations(): array
    {
        return [
            DocumentosRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('documentos');
    }

    public static function scopeEloquentQueryToTenant(Builder $query, ?Model $tenant): Builder
    {
        $user = Auth::user();

        if (
            $user instanceof User
            && $user->hasGlobalAyuntamientosAccess()
            && Filament::getCurrentPanel()?->getId() === 'ayuntamientos'
        ) {
            return $query;
        }

        return parent::scopeEloquentQueryToTenant($query, $tenant);
    }
}
