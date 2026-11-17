<?php

declare(strict_types=1);

namespace App\Filament\Ayuntamientos\Resources\Expedientes\RelationManagers;

use App\Filament\Support\RealizarSubsanacionAction;
use App\Models\Subsanacion;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Subsanaciones del expediente en el portal de Ayuntamientos.
 *
 * Lista la tabla única `subsanaciones`, de modo que se ven en una misma tabla
 * tanto las solicitudes de subsanación realizadas desde el portal de Obras
 * como las subsanaciones ya resueltas. Las líneas no subsanadas incluyen la
 * acción «Realizar subsanación», que abre el formulario de subsanación y
 * registra la fecha de subsanación automáticamente con la fecha actual.
 */
class SubsanacionesDocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'subsanaciones';

    protected static ?string $title = 'Subsanaciones';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('motivo_subsanacion')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with(['documento', 'documentoSubsanacion']))
            ->defaultSort('fecha_solicitud_subsanacion', 'desc')
            ->columns([
                TextColumn::make('documento.descripcion')
                    ->label('Documento a subsanar')
                    ->state(fn (Subsanacion $record): string => $record->documento?->nombre_documento
                        ?: $record->documento?->descripcion
                        ?: 'Documento '.$record->documento_id)
                    ->wrap(),
                TextColumn::make('motivo_subsanacion')
                    ->label('Motivo')
                    ->limit(60),
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
                TextColumn::make('documentoSubsanacion.descripcion')
                    ->label('Documento aportado')
                    ->limit(40),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Subsanacion::ESTADO_SUBSANADA => 'success',
                        Subsanacion::ESTADO_VENCIDA => 'danger',
                        default => 'warning',
                    }),
            ])
            ->recordActions([
                RealizarSubsanacionAction::make(),
            ])
            ->recordClasses(fn (Subsanacion $record): ?string => $record->fecha_subsanacion === null
                ? 'bg-danger-50 dark:bg-danger-950/20'
                : null);
    }
}
