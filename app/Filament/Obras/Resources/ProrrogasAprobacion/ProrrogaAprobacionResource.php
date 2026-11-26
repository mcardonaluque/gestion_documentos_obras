<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ProrrogasAprobacion;

use App\Enums\ProrrogaTipo;
use App\Filament\Obras\Resources\Concerns\HasAssignedExpedienteVisibility;
use App\Filament\Obras\Resources\ProrrogasAprobacion\Pages\EditProrrogaAprobacion;
use App\Filament\Obras\Resources\ProrrogasAprobacion\Pages\ListProrrogasAprobacion;
use App\Filament\Obras\Resources\ProrrogasAprobacion\RelationManagers\ProrrogasAprobacionRelationManager;
use App\Filament\Traits\CommonFilters;
use App\Filament\Traits\MunicipiosFilter;
use App\Models\DatosDeInicioDeObras;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Prórrogas y plazos de la fase de aprobación de obras (menú «Aprobación de Obras»).
 *
 * Lista las obras de Inicio de Obras ({@see DatosDeInicioDeObras}) y gestiona
 * sus prórrogas de normativa, proyecto y documentación en
 * {@see ProrrogasAprobacionRelationManager}. Las prórrogas de ejecución se
 * gestionan en {@see \App\Filament\Obras\Resources\Prorrogas\ProrrogaResource}.
 */
class ProrrogaAprobacionResource extends Resource
{
    use CommonFilters;
    use MunicipiosFilter;
    use HasAssignedExpedienteVisibility;

    protected static ?string $model = DatosDeInicioDeObras::class;

    protected static ?string $slug = 'prorrogas-aprobacion';

    protected static string | \UnitEnum | null $navigationGroup = 'Aprobacion de Obras';

    protected static ?int $navigationSort = 25;

    protected static ?string $navigationLabel = 'Prórrogas y plazos';

    protected static ?string $modelLabel = 'Prórroga de aprobación';

    protected static ?string $pluralModelLabel = 'Prórrogas de aprobación';

    protected static ?string $recordTitleAttribute = 'expediente_id';

    /**
     * Obras de inicio con expediente asociado.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select('DatosInicioDeObras.*')
            ->whereNotNull('DatosInicioDeObras.expediente_id')
            ->where('DatosInicioDeObras.expediente_id', '<>', '');
    }

    /**
     * Datos informativos de la obra sobre la que se gestionan las prórrogas.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextInput::make('Codigo_Plan')->label('Código plan')->disabled(),
                TextInput::make('numero_obra')->label('Obra')->disabled(),
                TextInput::make('subreferencia')->label('Subref.')->disabled(),
                TextInput::make('ao_ejecucion')->label('Año')->disabled(),
                TextInput::make('nombre_plan_info')
                    ->label('Nombre plan')
                    ->readOnly()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (TextInput $component, ?DatosDeInicioDeObras $record): void {
                        $component->state($record?->planes?->denominacion_plan ?? 'Sin plan');
                    })
                    ->columnSpanFull(),
                TextInput::make('nombre_obra1')->label('Nombre obra')->disabled()->columnSpanFull(),
                TextInput::make('expediente_id')->label('Expediente')->disabled()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Codigo_Plan')
                    ->label('Plan')
                    ->searchable(),
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
                TextColumn::make('nombre_obra1')
                    ->label('Nombre obra')
                    ->searchable(),
                TextColumn::make('municipios.nombre_municipio')
                    ->label('Municipio'),
                TextColumn::make('prorrogas_aprobacion_count')
                    ->label('Prórrogas')
                    ->counts([
                        'prorrogas as prorrogas_aprobacion_count' => fn (Builder $query): Builder => $query
                            ->whereIn('tipo_prorroga', ProrrogaTipo::valoresAprobacion()),
                    ])
                    ->badge(),
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
            ])
            ->filters([
                ...self::getCommonFilters(),
                self::getMunicipioFromInicioObrasFilter(),
                self::assignedExpedientesFilter(),
                Filter::make('con_prorrogas_aprobacion')
                    ->label('Con prórrogas de aprobación')
                    ->schema([
                        Toggle::make('enabled')->label('Solo con prórrogas')->default(false),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => ((bool) ($data['enabled'] ?? false))
                        ? $query->whereHas('prorrogas', fn (Builder $q): Builder => $q->whereIn('tipo_prorroga', ProrrogaTipo::valoresAprobacion()))
                        : $query)
                    ->indicateUsing(static fn (array $data): ?string => ((bool) ($data['enabled'] ?? false)) ? 'Con prórrogas' : null),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make()->label('Gestionar prórrogas'),
            ])
            ->recordUrl(fn (DatosDeInicioDeObras $record): string => static::getUrl('edit', ['record' => $record]))
            ->defaultSort('ao_ejecucion', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ProrrogasAprobacionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProrrogasAprobacion::route('/'),
            'edit' => EditProrrogaAprobacion::route('/{record}/edit'),
        ];
    }
}
