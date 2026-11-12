<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosCeroAprobacion;

use App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages\CreateModeloCeroAprobacion;
use App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages\EditModeloCeroAprobacion;
use App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages\ListModelosCeroAprobacion;
use App\Models\Expediente;
use App\Models\ModeloCeroAprobacion;
use App\Services\Aprobacion\ModeloCeroAprobacionDocumentService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->getSearchResultsUsing(
                        fn (string $search): array => static::expedienteQuery()
                            ->where('expediente_id', 'like', '%'.$search.'%')
                            ->orderBy('expediente_id')
                            ->limit(50)
                            ->pluck('expediente_id', 'expediente_id')
                            ->all(),
                    )
                    ->getOptionLabelUsing(
                        fn (string $value): ?string => static::expedienteQuery()
                            ->where('expediente_id', $value)
                            ->value('expediente_id'),
                    )
                    ->disabledOn('edit'),
                TextInput::make('anualidad')
                    ->label('Anualidad')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100),
                TextInput::make('secretario_nombre')
                    ->label('Secretario/a o secretario/a-interventor/a')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                TextInput::make('entidad_local')
                    ->label('Entidad local')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Repeater::make('documentos')
                    ->label('Documentos electrónicos')
                    ->relationship()
                    ->schema([
                        TextInput::make('referencia')
                            ->label('Ref.')
                            ->maxLength(255),
                        Textarea::make('contenido')
                            ->label('Contenido')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpan(2),
                        TextInput::make('denominacion')
                            ->label('Documento (denominación)')
                            ->required()
                            ->maxLength(1000)
                            ->columnSpan(2),
                        TextInput::make('csv')
                            ->label('Código seguro de verificación')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->orderColumn('orden')
                    ->defaultItems(1)
                    ->addActionLabel('Añadir documento')
                    ->reorderableWithButtons()
                    ->required()
                    ->minItems(1)
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
                    ->label('Generar DOCX')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(
                        function (
                            ModeloCeroAprobacion $record,
                            ModeloCeroAprobacionDocumentService $documentService,
                        ): ?BinaryFileResponse {
                            try {
                                return $documentService->download($record);
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
}
