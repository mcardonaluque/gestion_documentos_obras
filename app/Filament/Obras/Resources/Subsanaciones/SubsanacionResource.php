<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones;

use App\Filament\Obras\Resources\Subsanaciones\Pages\CreateSubsanacion;
use App\Filament\Obras\Resources\Subsanaciones\Pages\EditSubsanacion;
use App\Filament\Obras\Resources\Subsanaciones\Pages\ListSubsanacions;
use App\Filament\Obras\Resources\Subsanaciones\Schemas\SubsanacionForm;
use App\Models\Subsanacion;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Recurso del panel Obras para las solicitudes de subsanación de documentos.
 *
 * Gestiona el modelo {@see Subsanacion} (slug `subsanaciones`, grupo
 * «Documentación»): listado con estado derivado (pendiente, subsanada o
 * vencida), filtros por plan, año y estado, y formulario compartido con el
 * relation manager del expediente mediante {@see SubsanacionForm}.
 */
class SubsanacionResource extends Resource
{
    protected static ?string $model = Subsanacion::class;

    protected static ?string $slug = 'subsanaciones';

    protected static ?string $modelLabel = 'Subsanación';

    protected static ?string $pluralModelLabel = 'Subsanaciones';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-check';

    protected static string | \UnitEnum | null $navigationGroup = 'Documentación';

    /** Formulario de alta/edición compartido mediante SubsanacionForm. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components(SubsanacionForm::components());
    }

    /** Precarga los documentos referenciados para evitar consultas N+1 en el listado. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['documento', 'documentoSubsanacion']);
    }

    /** Tabla de seguimiento de subsanaciones con estado derivado y filtros. */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('Codigo_Plan')
                    ->label('Plan'),
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
                TextColumn::make('documento.descripcion')
                    ->label('Documento a subsanar')
                    ->limit(40),
                TextColumn::make('fecha_solicitud_subsanacion')
                    ->label('Solicitud')
                    ->date()
                    ->sortable(),
                TextColumn::make('plazo_subsanacion')
                    ->label('Plazo (días)')
                    ->numeric(),
                TextColumn::make('fecha_subsanacion')
                    ->label('Subsanación')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Subsanacion::ESTADO_SUBSANADA => 'success',
                        Subsanacion::ESTADO_VENCIDA => 'danger',
                        default => 'warning',
                    }),
            ])
            ->defaultSort('fecha_solicitud_subsanacion', 'desc')
            ->filters([
                SelectFilter::make('Codigo_Plan')
                    ->label('Plan')
                    ->options(
                        fn (): array => Subsanacion::query()
                            ->whereNotNull('Codigo_Plan')
                            ->distinct()
                            ->orderBy('Codigo_Plan')
                            ->pluck('Codigo_Plan', 'Codigo_Plan')
                            ->all()
                    ),
                SelectFilter::make('ao_ejecucion')
                    ->label('Año de ejecución')
                    ->options(
                        fn (): array => Subsanacion::query()
                            ->whereNotNull('ao_ejecucion')
                            ->distinct()
                            ->orderByDesc('ao_ejecucion')
                            ->pluck('ao_ejecucion', 'ao_ejecucion')
                            ->all()
                    ),
                TernaryFilter::make('pendientes')
                    ->label('Estado')
                    ->trueLabel('Pendientes de subsanar')
                    ->falseLabel('Subsanadas')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNull('fecha_subsanacion'),
                        false: fn (Builder $query): Builder => $query->whereNotNull('fecha_subsanacion'),
                    ),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** Páginas registradas del recurso. */
    public static function getPages(): array
    {
        return [
            'index' => ListSubsanacions::route('/'),
            'create' => CreateSubsanacion::route('/create'),
            'edit' => EditSubsanacion::route('/{record}/edit'),
        ];
    }
}
