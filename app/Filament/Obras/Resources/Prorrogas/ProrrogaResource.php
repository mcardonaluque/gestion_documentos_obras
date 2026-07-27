<?php

namespace App\Filament\Obras\Resources\Prorrogas;

use App\Filament\Obras\Resources\Concerns\HasAssignedExpedienteVisibility;
use App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers\ProrrogasRelationManager;
use App\Filament\Obras\Resources\Prorrogas\Pages\EditProrroga;
use App\Filament\Obras\Resources\Prorrogas\Pages\ListProrrogas;
use App\Filament\Traits\CommonFilters;
use App\Filament\Traits\MunicipiosFilter;
use App\Models\DatosEjecucionObras;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProrrogaResource extends Resource
{
    use CommonFilters;
    use MunicipiosFilter;
    use HasAssignedExpedienteVisibility;

    protected static ?string $model = DatosEjecucionObras::class;

    protected static ?string $slug = 'prorrogas-ejecucion';

    protected static string | \UnitEnum | null $navigationGroup = 'Ejecucion';

    protected static ?int $navigationSort = 25;

    protected static ?string $navigationLabel = 'Prórrogas';

    protected static ?string $modelLabel = 'Prórroga de obra';

    protected static ?string $pluralModelLabel = 'Prórrogas de ejecución';

    protected static ?string $recordTitleAttribute = 'expediente_id';

    /**
     * La navegación se agrupa bajo Ejecución para ubicar la gestión de prórrogas.
     */
    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'Ejecucion';
    }

    /**
     * Limita el listado a expedientes de ejecución con expediente asociado.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select('Datos_Ejecucion_Obras.*')
            ->whereNotNull('Datos_Ejecucion_Obras.expediente_id')
            ->where('Datos_Ejecucion_Obras.expediente_id', '<>', '');
    }

    /**
     * Formulario informativo del expediente base sobre el que se gestionan las prórrogas.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextInput::make('codigo_plan_info')
                    ->label('Código plan')
                    ->readOnly()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (TextInput $component, ?DatosEjecucionObras $record): void {
                        $component->state($record?->Codigo_Plan ?? $record?->codigo_plan ?? '');
                    }),
                TextInput::make('numero_obra')->label('Obra')->disabled(),
                TextInput::make('subreferencia')->label('Subref.')->disabled(),
                TextInput::make('ao_ejecucion')->label('Año')->disabled(),
                TextInput::make('nombre_plan_info')
                    ->label('Nombre plan')
                    ->readOnly()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (TextInput $component, ?DatosEjecucionObras $record): void {
                        $component->state($record?->planes?->denominacion_plan ?? 'Sin plan');
                    })
                    ->columnSpanFull(),
                TextInput::make('nombre_obra_info')
                    ->label('Nombre obra')
                    ->readOnly()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (TextInput $component, ?DatosEjecucionObras $record): void {
                        $component->state($record?->obra?->nombre_obra1 ?? 'Sin nombre');
                    })
                    ->columnSpanFull(),
                TextInput::make('expediente_id')->label('Expediente')->disabled()->columnSpanFull(),
            ]);
    }

    /**
     * Tabla principal de gestión de expedientes con acceso a las prórrogas.
     */
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
                TextColumn::make('obra.nombre_obra1')
                    ->label('Nombre obra')
                    ->searchable(),
                TextColumn::make('obra.municipios.nombre_municipio')
                    ->label('Municipio')
                    ->searchable(),
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
            ])
            ->filters([
                ...self::getCommonFilters(),
                self::getMunicipioFromInicioObrasFilter(),
                self::assignedExpedientesFilter(),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make()->label('Gestionar prórrogas'),
            ])
            ->recordUrl(fn (DatosEjecucionObras $record): string => static::getUrl('edit', ['record' => $record]))
            ->defaultSort('ao_ejecucion', 'desc');
    }

    /**
     * La gestión detallada de prórrogas se expone en el relation manager asociado.
     */
    public static function getRelations(): array
    {
        return [
            ProrrogasRelationManager::class,
        ];
    }

    /**
     * Páginas del recurso para listar y editar el expediente gestor de prórrogas.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListProrrogas::route('/'),
            'edit' => EditProrroga::route('/{record}/edit'),
        ];
    }
}
