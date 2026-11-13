<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosTresAprobacion;

use App\Filament\Obras\Resources\ModelosTresAprobacion\Pages\CreateModeloTresAprobacion;
use App\Filament\Obras\Resources\ModelosTresAprobacion\Pages\EditModeloTresAprobacion;
use App\Filament\Obras\Resources\ModelosTresAprobacion\Pages\ListModelosTresAprobacion;
use App\Models\DatosDeInicioDeObras;
use App\Models\ModeloTresAprobacion;
use App\Services\Aprobacion\ModeloTresAprobacionContextService;
use App\Services\Aprobacion\ModeloTresAprobacionDocumentService;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ModeloTresAprobacionResource extends Resource
{
    protected static ?string $model = ModeloTresAprobacion::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $slug = 'modelos-tres-aprobacion';

    protected static ?string $modelLabel = 'Modelo 3 de aprobación';

    protected static ?string $pluralModelLabel = 'Modelos 3 de aprobación';

    protected static ?string $navigationLabel = 'Modelo 3';

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
                ->afterStateUpdated(function (?string $state, callable $set, ModeloTresAprobacionContextService $context): void {
                    if (blank($state)) {
                        return;
                    }

                    $data = $context->contextForExpediente($state);
                    $set('entidad_local', $data['entidad_local']);
                    $set('secretario_nombre', $data['secretario_nombre']);
                    $set('importe_aprobado_ayuntamiento', $data['importe_aprobado_ayuntamiento']);
                    $set('partida_presupuestaria', $data['partida_presupuestaria']);
                })
                ->disabledOn('edit')
                ->columnSpan(2),
            Select::make('modalidad_presupuesto')
                ->label('Modalidad presupuestaria')
                ->required()
                ->options([
                    'vigente' => 'Presupuesto en vigor (Modelo 3A)',
                    'anticipado' => 'Crédito anticipado (Modelo 3B)',
                ])
                ->live()
                ->columnSpan(2),
            TextInput::make('secretario_nombre')
                ->label('Secretario/a o secretario/a-interventor/a')
                ->required()
                ->maxLength(255)
                ->columnSpan(2),
            Select::make('categoria_secretario')
                ->label('Cargo o categoría')
                ->options([
                    'Secretario' => 'Secretario',
                    'Secretario-Interventor' => 'Secretario-Interventor',
                ])
                ->columnSpan(2),
            TextInput::make('entidad_local')
                ->label('Entidad local')
                ->disabled()
                ->dehydrated()
                ->columnSpan(2),
            TextInput::make('importe_aprobado_ayuntamiento')
                ->label('Importe aprobado del ayuntamiento')
                ->disabled()
                ->dehydrated()
                ->prefix('€')
                ->columnSpan(1),
            TextInput::make('partida_presupuestaria')
                ->label('Partida presupuestaria')
                ->disabled()
                ->dehydrated()
                ->columnSpan(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('created_at', 'desc')->columns([
            TextColumn::make('expediente_id')->label('Expediente')->searchable(),
            TextColumn::make('codigo_plan')->label('Plan')->searchable(),
            TextColumn::make('numero_obra')->label('Obra')->numeric(),
            TextColumn::make('subreferencia')->label('Subref.')->numeric(),
            TextColumn::make('ao_ejecucion')->label('Año')->numeric(),
            TextColumn::make('nombre_obra')->label('Nombre de obra')->limit(50),
            TextColumn::make('modalidad_presupuesto')
                ->label('Modalidad')
                ->formatStateUsing(fn (string $state): string => $state === 'anticipado' ? 'Modelo 3B' : 'Modelo 3A')
                ->badge(),
            TextColumn::make('anualidad')->label('Anualidad')->numeric(),
        ])->recordActions([
            Action::make('generarDocumento')
                ->label(fn (): string => Filament::getCurrentPanel()?->getId() === 'ayuntamientos' ? 'Generar PDF' : 'Generar DOCX')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (ModeloTresAprobacion $record, ModeloTresAprobacionDocumentService $service): ?BinaryFileResponse {
                    try {
                        return $service->download($record, Filament::getCurrentPanel()?->getId() === 'ayuntamientos');
                    } catch (RuntimeException $exception) {
                        Notification::make()
                            ->danger()
                            ->title('No se pudo generar el Modelo 3 de aprobación')
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
            ->orderBy('expediente_id')
            ->limit(100)
            ->get()
            ->mapWithKeys(fn (DatosDeInicioDeObras $obra): array => [
                (string) $obra->expediente_id => sprintf('%s - %s', $obra->expediente_id, $obra->nombre_obra1),
            ])
            ->all();
    }

    /** @return Builder<ModeloTresAprobacion> */
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
            'index' => ListModelosTresAprobacion::route('/'),
            'create' => CreateModeloTresAprobacion::route('/create'),
            'edit' => EditModeloTresAprobacion::route('/{record}/edit'),
        ];
    }
}
