<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosDosAprobacion;

use App\Filament\Obras\Resources\ModelosDosAprobacion\Pages\CreateModeloDosAprobacion;
use App\Filament\Obras\Resources\ModelosDosAprobacion\Pages\EditModeloDosAprobacion;
use App\Filament\Obras\Resources\ModelosDosAprobacion\Pages\ListModelosDosAprobacion;
use App\Models\DatosDeInicioDeObras;
use App\Models\ModeloDosAprobacion;
use App\Services\Aprobacion\ModeloDosAprobacionContextService;
use App\Services\Aprobacion\ModeloDosAprobacionDocumentService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ModeloDosAprobacionResource extends Resource
{
    protected static ?string $model = ModeloDosAprobacion::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $slug = 'modelos-dos-aprobacion';

    protected static ?string $modelLabel = 'Modelo 2 de aprobación';

    protected static ?string $pluralModelLabel = 'Modelos 2 de aprobación';

    protected static ?string $navigationLabel = 'Modelo 2';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Aprobación';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(4)->components([
            Select::make('expediente_id')
                ->label('Expediente')
                ->required()
                ->searchable()
                ->options(fn (): array => static::expedienteOptions())
                ->preload()
                ->live()
                ->afterStateUpdated(function (
                    ?string $state,
                    callable $set,
                    ModeloDosAprobacionContextService $contextService,
                ): void {
                    if (blank($state)) {
                        return;
                    }

                    $data = $contextService->contextForExpediente($state);
                    $set('entidad_local', $data['entidad_local']);
                    $set('secretario_nombre', $data['secretario_nombre']);
                })
                ->disabledOn('edit')
                ->columnSpan(2),
            TextInput::make('secretario_nombre')
                ->label('Secretario/a o secretario/a-interventor/a')
                ->required()
                ->maxLength(255)
                ->columnSpan(2),
            TextInput::make('entidad_local')
                ->label('Entidad local')
                ->disabled()
                ->dehydrated()
                ->columnSpan(2),
            Toggle::make('normativa_cumplida')
                ->label('Cumple la normativa urbanística y ambiental en vigor')
                ->default(false)
                ->required()
                ->columnSpan(1),
            Toggle::make('terrenos_disponibles')
                ->label('Los terrenos están disponibles')
                ->default(false)
                ->required()
                ->columnSpan(1),
            Select::make('autorizaciones')
                ->label('Autorizaciones o concesiones administrativas')
                ->required()
                ->options([
                    'no_requiere' => 'No requiere autorizaciones o concesiones administrativas precisas para su ejecución.',
                    'cuentan' => 'Cuentan con autorizaciones o concesiones administrativas precisas para su ejecución.',
                    'tramite' => 'Las autorizaciones o concesiones administrativas precisas para su ejecución se encuentran en trámite.',
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('created_at', 'desc')->columns([
            TextColumn::make('expediente_id')->label('Expediente')->searchable(),
            TextColumn::make('codigo_plan')->label('Programa')->searchable(),
            TextColumn::make('ao_ejecucion')->label('Anualidad')->numeric(),
            TextColumn::make('nombre_obra')->label('Actuación')->limit(50),
            TextColumn::make('codigo_municipio')->label('Cód. ayuntamiento')->numeric(),
            IconColumn::make('normativa_cumplida')->label('Normativa')->boolean(),
            IconColumn::make('terrenos_disponibles')->label('Disponibilidad')->boolean(),
            TextColumn::make('autorizaciones')
                ->label('Autorizaciones')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'no_requiere' => 'No requiere',
                    'cuentan' => 'Concedidas',
                    'tramite' => 'En trámite',
                })
                ->badge(),
        ])->recordActions([
            Action::make('generarDocumento')
                ->label(fn (): string => Filament::getCurrentPanel()?->getId() === 'ayuntamientos' ? 'Regenerar y descargar PDF' : 'Regenerar y descargar DOCX')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (ModeloDosAprobacion $record, ModeloDosAprobacionDocumentService $service): ?BinaryFileResponse {
                    try {
                        return $service->download($record, Filament::getCurrentPanel()?->getId() === 'ayuntamientos');
                    } catch (RuntimeException $exception) {
                        Notification::make()
                            ->danger()
                            ->title('No se pudo generar el Modelo 2 de aprobación')
                            ->body($exception->getMessage())
                            ->send();

                        return null;
                    }
                }),
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    /** @return Builder<DatosDeInicioDeObras> */
    private static function expedienteQuery(): Builder
    {
        $query = DatosDeInicioDeObras::query();
        $tenant = Filament::getTenant();

        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $tenant instanceof Model) {
            $query->where('team_id', $tenant->getKey());
        }

        return $query;
    }

    /** @return array<string, string> */
    private static function expedienteOptions(): array
    {
        return static::expedienteQuery()
            ->select(['expediente_id', 'nombre_obra1'])
            ->orderByDesc('ao_ejecucion')
            ->orderByDesc('expediente_id')
            ->limit(100)
            ->get()
            ->mapWithKeys(fn (DatosDeInicioDeObras $obra): array => [
                (string) $obra->expediente_id => sprintf('%s - %s', $obra->expediente_id, $obra->nombre_obra1),
            ])
            ->all();
    }

    /** @return Builder<ModeloDosAprobacion> */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $tenant = Filament::getTenant();

        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $tenant instanceof Model) {
            $query->where('team_id', $tenant->getKey());
        }

        return $query;
    }

    /** @return array<string, class-string> */
    public static function getPages(): array
    {
        return [
            'index' => ListModelosDosAprobacion::route('/'),
            'create' => CreateModeloDosAprobacion::route('/create'),
            'edit' => EditModeloDosAprobacion::route('/{record}/edit'),
        ];
    }
}
