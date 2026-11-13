<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosCeroAprobacion;

use App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages\CreateModeloCeroAprobacion;
use App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages\EditModeloCeroAprobacion;
use App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages\ListModelosCeroAprobacion;
use App\Filament\Obras\Resources\ModelosCeroAprobacion\RelationManagers\DocumentosRelationManager;
use App\Models\Expediente;
use App\Models\ModeloCeroAprobacion;
use App\Services\Aprobacion\ModeloCeroAprobacionContextService;
use App\Services\Aprobacion\ModeloCeroAprobacionDocumentService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Recurso de Obras para la aprobación documental Modelo 0.
 */
class ModeloCeroAprobacionResource extends Resource
{
    protected static ?string $model = ModeloCeroAprobacion::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $slug = 'modelos-cero-aprobacion';

    protected static ?string $modelLabel = 'Modelo 0 de aprobación';

    protected static ?string $pluralModelLabel = 'Modelos 0 de aprobación';

    protected static ?string $navigationLabel = 'Modelo 0';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Aprobación';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Select::make('expediente_id')
                    ->label('Expediente')
                    ->required()
                    ->searchable()
                    ->options(fn (): array => static::expedienteOptions())
                    ->optionsLimit(100)
                    ->preload()
                    ->getSearchResultsUsing(
                        fn (string $search): array => static::expedienteOptions($search),
                    )
                    ->getOptionLabelUsing(
                        fn (string $value): ?string => static::expedienteOptions($value)[$value] ?? null,
                    )
                    ->live()
                    ->afterStateUpdated(function (
                        ?string $state,
                        callable $set,
                        ModeloCeroAprobacionContextService $contextService,
                    ): void {
                        if (blank($state)) {
                            $set('entidad_local', null);
                            $set('secretario_nombre', null);

                            return;
                        }

                        $context = $contextService->contextForExpediente($state);

                        $set('entidad_local', $context['entidad_local']);
                        $set('secretario_nombre', $context['secretario_nombre']);
                        $set('anualidad', $context['ao_ejecucion']);

                        if (blank($context['secretario_nombre'])) {
                            Notification::make()
                                ->warning()
                                ->title('Falta el secretario del ayuntamiento')
                                ->body('Complete el campo Secretario en TablaDeMunicipios antes de generar el documento Word.')
                                ->persistent()
                                ->send();
                        }
                    })
                    ->disabledOn('edit'),
                TextInput::make('anualidad')
                    ->label('Anualidad')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100),
                TextInput::make('secretario_nombre')
                    ->label('Secretario/a o secretario/a-interventor/a')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Se completa al seleccionar el expediente')
                    ->columnSpan(2),
                TextInput::make('entidad_local')
                    ->label('Entidad local')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Se completa al seleccionar el expediente')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query): Builder => $query->withCount('documentos'),
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('codigo_plan')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('numero_obra')
                    ->label('Obra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->label('Subref.')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->label('Año')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombre_obra')
                    ->label('Nombre de obra')
                    ->limit(50)
                    ->tooltip(fn (ModeloCeroAprobacion $record): ?string => $record->nombre_obra),
                TextColumn::make('anualidad')
                    ->label('Anualidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('documentos_count')
                    ->label('Documentos')
                    ->numeric(),
            ])
            ->filters([
                SelectFilter::make('codigo_plan')
                    ->label('Plan')
                    ->options(
                        fn (): array => ModeloCeroAprobacion::query()
                            ->whereNotNull('codigo_plan')
                            ->distinct()
                            ->orderBy('codigo_plan')
                            ->pluck('codigo_plan', 'codigo_plan')
                            ->all(),
                    ),
                SelectFilter::make('ao_ejecucion')
                    ->label('Año de ejecución')
                    ->options(
                        fn (): array => ModeloCeroAprobacion::query()
                            ->whereNotNull('ao_ejecucion')
                            ->distinct()
                            ->orderByDesc('ao_ejecucion')
                            ->pluck('ao_ejecucion', 'ao_ejecucion')
                            ->all(),
                    ),
            ])
            ->recordActions([
                Action::make('generarDocx')
                    ->label(
                        fn (): string => Filament::getCurrentPanel()?->getId() === 'ayuntamientos'
                            ? 'Generar PDF'
                            : 'Generar DOCX',
                    )
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(
                        function (
                            ModeloCeroAprobacion $record,
                            ModeloCeroAprobacionDocumentService $documentService,
                        ): ?BinaryFileResponse {
                            try {
                                return $documentService->download(
                                    $record,
                                    Filament::getCurrentPanel()?->getId() === 'ayuntamientos',
                                );
                            } catch (RuntimeException $exception) {
                                Notification::make()
                                    ->danger()
                                    ->title('No se pudo generar el Modelo 0 de aprobación')
                                    ->body($exception->getMessage())
                                    ->send();

                                return null;
                            }
                        },
                    ),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    /**
     * Restringe los expedientes seleccionables al ayuntamiento activo cuando
     * este recurso se registra en el panel municipal.
     *
     * @return Builder<Expediente>
     */
    private static function expedienteQuery(): Builder
    {
        $query = Expediente::query();
        $tenant = Filament::getTenant();

        if (
            Filament::getCurrentPanel()?->getId() === 'ayuntamientos'
            && $tenant instanceof Model
        ) {
            $query->where('team_id', $tenant->getKey());
        }

        return $query;
    }

    /**
     * @return array<string, string>
     */
    private static function expedienteOptions(?string $search = null): array
    {
        $query = static::expedienteQuery()
            ->select(['expediente_id', 'nombre_obra'])
            ->orderBy('expediente_id');

        if ($search !== null && $search !== '') {
            $query->where('expediente_id', 'like', '%'.$search.'%');
        }

        return $query
            ->limit(100)
            ->get()
            ->mapWithKeys(
                fn (Expediente $expediente): array => [
                    (string) $expediente->expediente_id => static::expedienteLabel($expediente),
                ],
            )
            ->all();
    }

    private static function expedienteLabel(Expediente $expediente): string
    {
        $nombreObra = trim((string) $expediente->nombre_obra);

        return $nombreObra === ''
            ? (string) $expediente->expediente_id
            : sprintf('%s - %s', $expediente->expediente_id, $nombreObra);
    }

    /**
     * Restringe el listado del panel de Ayuntamientos al tenant actual.
     *
     * @return Builder<ModeloCeroAprobacion>
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $tenant = Filament::getTenant();

        if (
            Filament::getCurrentPanel()?->getId() === 'ayuntamientos'
            && $tenant instanceof Model
        ) {
            $query->where('team_id', $tenant->getKey());
        }

        return $query;
    }

    /**
     * @return array<string, class-string>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListModelosCeroAprobacion::route('/'),
            'create' => CreateModeloCeroAprobacion::route('/create'),
            'edit' => EditModeloCeroAprobacion::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<class-string>
     */
    public static function getRelations(): array
    {
        return [
            DocumentosRelationManager::class,
        ];
    }
}
