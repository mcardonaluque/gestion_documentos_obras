<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\SubsanacionesGenerales;

use App\Filament\Obras\Resources\SubsanacionesGenerales\Pages\ListSubsanacionesGenerales;
use App\Filament\Support\SolicitarSubsanacionDocumentoAction;
use App\Models\DocumentoExpediente;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Vista general de subsanaciones del panel Obras (menú «Aprobacion de Obras»).
 *
 * Lista los documentos de los expedientes mostrando su estado respecto a la
 * tabla única `subsanaciones`: los que tienen subsanación solicitada (leyenda
 * «Subsanación solicitada»), los ya subsanados y aquellos a los que se les
 * podría solicitar subsanación mediante la acción de línea
 * {@see SolicitarSubsanacionDocumentoAction}, que registra la solicitud con la
 * fecha actual de forma automática. Mientras exista una solicitud sin
 * subsanar, la acción no se muestra.
 */
class SubsanacionGeneralResource extends Resource
{
    protected static ?string $model = DocumentoExpediente::class;

    protected static ?string $slug = 'subsanaciones-generales';

    protected static ?string $modelLabel = 'Subsanación de documento';

    protected static ?string $pluralModelLabel = 'Subsanaciones de documentos';

    protected static ?string $navigationLabel = 'Subsanaciones';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-check';

    protected static string | \UnitEnum | null $navigationGroup = 'Aprobacion de Obras';

    /** Documentos con expediente asociado, sobre los que puede pedirse subsanación. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('expediente_id')
            ->where('expediente_id', '<>', '');
    }

    /** Tabla general de documentos con su estado de subsanación y acción de solicitud. */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('Codigo_Plan')
                    ->label('Plan'),
                TextColumn::make('referencia')
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
                TextColumn::make('nombre_documento')
                    ->label('Documento')
                    ->state(fn (DocumentoExpediente $record): string => $record->nombre_documento
                        ?: $record->descripcion
                        ?: 'Documento '.$record->getKey())
                    ->searchable(['nombre_documento', 'descripcion'])
                    ->wrap(),
                TextColumn::make('fechaincorporacion')
                    ->label('Incorporación')
                    ->date()
                    ->sortable(),
                TextColumn::make('fecha_solicitud_subsanacion')
                    ->label('Solicitud subsanación')
                    ->date()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('fecha_subsanacion')
                    ->label('Fecha subsanación')
                    ->date()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('estado_subsanacion')
                    ->label('Subsanación')
                    ->state(fn (DocumentoExpediente $record): string => $record->estado_subsanacion ?? 'Sin solicitar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Subsanación solicitada' => 'warning',
                        'Subsanada' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('fecha_solicitud_subsanacion', 'desc')
            ->filters([
                SelectFilter::make('Codigo_Plan')
                    ->label('Plan')
                    ->options(
                        fn (): array => DocumentoExpediente::query()
                            ->whereNotNull('Codigo_Plan')
                            ->distinct()
                            ->orderBy('Codigo_Plan')
                            ->pluck('Codigo_Plan', 'Codigo_Plan')
                            ->all()
                    ),
                SelectFilter::make('ao_ejecucion')
                    ->label('Año de ejecución')
                    ->options(
                        fn (): array => DocumentoExpediente::query()
                            ->whereNotNull('ao_ejecucion')
                            ->distinct()
                            ->orderByDesc('ao_ejecucion')
                            ->pluck('ao_ejecucion', 'ao_ejecucion')
                            ->all()
                    ),
                TernaryFilter::make('subsanacion')
                    ->label('Estado de subsanación')
                    ->trueLabel('Subsanación solicitada (pendiente)')
                    ->falseLabel('Subsanadas')
                    ->queries(
                        true: fn (Builder $query): Builder => $query
                            ->whereNotNull('fecha_solicitud_subsanacion')
                            ->whereNull('fecha_subsanacion'),
                        false: fn (Builder $query): Builder => $query
                            ->whereNotNull('fecha_subsanacion'),
                    ),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                // Oculta mientras exista una solicitud sin subsanar (leyenda «Subsanación solicitada»).
                SolicitarSubsanacionDocumentoAction::make(),
            ]);
    }

    /** Páginas registradas del recurso (solo listado). */
    public static function getPages(): array
    {
        return [
            'index' => ListSubsanacionesGenerales::route('/'),
        ];
    }
}
