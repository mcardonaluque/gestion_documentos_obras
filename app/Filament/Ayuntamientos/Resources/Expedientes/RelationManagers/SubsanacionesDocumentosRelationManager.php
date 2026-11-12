<?php

declare(strict_types=1);

namespace App\Filament\Ayuntamientos\Resources\Expedientes\RelationManagers;

use App\Models\DocumentoExpediente;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Muestra los documentos del expediente para los que se ha solicitado
 * subsanación, incluidos los que ya han sido subsanados.
 */
class SubsanacionesDocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'documentos';

    protected static ?string $title = 'Subsanaciones de documentos';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->whereNotNull('fecha_solicitud_subsanacion')
                ->with('tipodocumentos'))
            ->defaultSort('fecha_solicitud_subsanacion', 'desc')
            ->columns([
                TextColumn::make('nombre_documento')
                    ->label('Documento')
                    ->state(fn (DocumentoExpediente $record): string => $record->nombre_documento
                        ?: $record->descripcion
                        ?: $record->tipodocumentos?->nombre
                        ?: 'Documento '.$record->getKey())
                    ->wrap(),
                TextColumn::make('fecha_solicitud_subsanacion')
                    ->label('Solicitud')
                    ->date()
                    ->sortable(),
                TextColumn::make('fecha_subsanacion')
                    ->label('Subsanación')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado_subsanacion')
                    ->label('Estado')
                    ->state(fn (DocumentoExpediente $record): string => $record->fecha_subsanacion === null
                        ? 'Pendiente de subsanación'
                        : 'Subsanada')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Subsanada' ? 'success' : 'danger'),
            ])
            ->recordClasses(fn (DocumentoExpediente $record): ?string => $record->fecha_subsanacion === null
                ? 'bg-danger-50 dark:bg-danger-950/20'
                : null);
    }
}
