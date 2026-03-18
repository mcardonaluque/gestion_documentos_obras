<?php

namespace App\Filament\Shared\Resources\Expedientes;

use App\Events\SystemEventOccurred;
use App\Filament\Shared\Resources\Expedientes\RelationManagers\DocumentosRelationManager;
use App\Models\DocumentoGenerico;
use App\Models\Expediente;
use App\Models\DocumentoExpediente;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

abstract class ExpedienteResourceBase extends Resource
{
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
                //
            ])
            ->recordActions([
                Action::make('subirDocumento')
                    ->label('Subir Documento')
                    ->icon('heroicon-o-paper-clip')
                    ->color('success')
                    ->schema([
                        TextInput::make('expediente_id')
                            ->label('Expediente')
                            ->readOnly()
                            ->default(fn ($record) => $record?->expediente_id),

                        TextInput::make('Codigo_Plan')
                            ->label('Código plan')
                            ->readOnly()
                            ->default(fn ($record) => $record?->codigo_plan),
                        TextInput::make('planes.denominacion_plan')
                            ->label('Plan')
                            ->readOnly()
                            ->default(fn ($record) => $record?->planes?->denominacion_plan),
                        TextInput::make('numero_obra')
                            ->label('Número obra')
                            ->readOnly()
                            ->default(fn ($record) => $record?->referencia),

                        TextInput::make('subreferencia')
                            ->readOnly()
                            ->default(fn ($record) => $record?->subreferencia),

                        TextInput::make('ao_ejecucion')
                            ->label('Año ejecución')
                            ->readOnly()
                            ->default(fn ($record) => $record?->ao_ejecucion),

                        TextInput::make('nsecuencia')
                            ->label('Nº secuencia')
                            ->readOnly()
                            ->default(fn ($record) => ((int) ($record?->documentos()?->max('nsecuencia') ?? 0)) + 1),

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
                            ->required()
                            ->default(now()),
                    ])
                    ->action(function (array $data, $record) {
                        $nextSecuencia = ((int) (DocumentoExpediente::query()
                            ->where('expediente_id', $record->expediente_id)
                            ->max('nsecuencia') ?? 0)) + 1;

                        $documento = $record->documentos()->create([
                            'expediente_id' => $record->expediente_id,
                            'cod_plan' => $record->codigo_plan,
                            'referencia' => $record->referencia,
                            'subreferencia' => $record->subreferencia,
                            'ao_ejecucion' => $record->ao_ejecucion,
                            'descripcion' => $data['descripcion'],
                            'cod_documento' => $data['cod_documento'],
                            'fechaincorporacion' => $data['created_at'],
                            'nsecuencia' => $nextSecuencia,
                            'csv' => $data['archivo'],
                            'estado' => 'Nuevo',
                        ]);

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
            ->defaultSort('expediente_id', 'asc')
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount('documentos')->with('documentos'))
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
}
