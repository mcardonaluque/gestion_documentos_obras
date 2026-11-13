<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosUnoAprobacion;

use App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages\CreateModeloUnoAprobacion;
use App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages\EditModeloUnoAprobacion;
use App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages\ListModelosUnoAprobacion;
use App\Models\DatosDeInicioDeObras;
use App\Models\ModeloUnoAprobacion;
use App\Services\Aprobacion\ModeloUnoAprobacionContextService;
use App\Services\Aprobacion\ModeloUnoAprobacionDocumentService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
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

class ModeloUnoAprobacionResource extends Resource
{
    protected static ?string $model = ModeloUnoAprobacion::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $slug = 'modelos-uno-aprobacion';

    protected static ?string $modelLabel = 'Modelo 1 de aprobación';

    protected static ?string $pluralModelLabel = 'Modelos 1 de aprobación';

    protected static ?string $navigationLabel = 'Modelo 1';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Aprobación';

    protected static ?int $navigationSort = 2;

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
                ->afterStateUpdated(function (?string $state, callable $set, ModeloUnoAprobacionContextService $context): void {
                    if ($state === null || $state === '') {
                        return;
                    }

                    $data = $context->contextForExpediente($state);
                    $set('entidad_local', $data['entidad_local']);
                    $set('secretario_nombre', $data['secretario_nombre']);
                })
                ->disabledOn('edit'),
            DatePicker::make('fecha_documento')
                ->label('Fecha del documento')
                ->required()
                ->default(now()),
            Select::make('tipo_competencia')
                ->label('Tipo de competencia')
                ->required()
                ->options([
                    'propia' => 'Propia',
                    'delegada' => 'Delegada',
                    'distinta' => 'Distinta de las propias y de las atribuidas por delegación',
                ])
                ->columnSpan(1),
            TextInput::make('secretario_nombre')
                ->label('Secretario/a o secretario/a-interventor/a')
                ->required()
                ->maxLength(255)
                ->columnSpan(1),
            TextInput::make('entidad_local')
                ->label('Entidad local')
                ->disabled()
                ->dehydrated()
               // ->columnSpanFull(),
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
            TextColumn::make('tipo_competencia')->label('Competencia')->badge(),
            TextColumn::make('fecha_documento')->label('Fecha')->date('d/m/Y'),
        ])->recordActions([
            Action::make('generarDocx')
                ->label(
                    fn (): string => Filament::getCurrentPanel()?->getId() === 'ayuntamientos'
                        ? 'Generar PDF'
                        : 'Generar DOCX',
                )
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (ModeloUnoAprobacion $record, ModeloUnoAprobacionDocumentService $service): ?BinaryFileResponse {
                    try {
                        return $service->download(
                            $record,
                            Filament::getCurrentPanel()?->getId() === 'ayuntamientos',
                        );
                    } catch (RuntimeException $exception) {
                        Notification::make()->danger()->title('No se pudo generar el Modelo 1')->body($exception->getMessage())->send();

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

    /** @return Builder<ModeloUnoAprobacion> */
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
            'index' => ListModelosUnoAprobacion::route('/'),
            'create' => CreateModeloUnoAprobacion::route('/create'),
            'edit' => EditModeloUnoAprobacion::route('/{record}/edit'),
        ];
    }
}
