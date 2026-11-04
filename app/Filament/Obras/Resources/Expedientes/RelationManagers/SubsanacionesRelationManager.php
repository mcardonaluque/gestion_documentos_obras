<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Expedientes\RelationManagers;

use App\Filament\Obras\Resources\Subsanaciones\Schemas\SubsanacionForm;
use App\Models\Subsanacion;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Relation manager de las subsanaciones ('subsanaciones') de los documentos
 * de un expediente: alta y seguimiento de solicitudes de subsanación con su
 * documento requerido, plazo, fechas y estado derivado. Reutiliza el schema
 * {@see SubsanacionForm} del recurso SubsanacionResource. Registrado en
 * ExpedienteResourceBase (paneles Obras y Ayuntamientos).
 */
class SubsanacionesRelationManager extends RelationManager
{
    /** Relación de subsanaciones del expediente propietario. */
    protected static string $relationship = 'subsanaciones';

    /** Título visible del bloque de relación en Filament. */
    protected static ?string $title = 'Subsanaciones de documentos';

    /** Formulario compartido, con los documentos acotados al expediente propietario. */
    public function form(Schema $schema): Schema
    {
        /** @var string $expedienteId */
        $expedienteId = $this->getOwnerRecord()->getAttribute('expediente_id');

        return $schema
            ->columns(3)
            ->components(SubsanacionForm::components($expedienteId));
    }

    /** Tabla de seguimiento de las subsanaciones del expediente. */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('motivo_subsanacion')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['documento', 'documentoSubsanacion']))
            ->defaultSort('fecha_solicitud_subsanacion', 'desc')
            ->columns([
                TextColumn::make('documento.descripcion')
                    ->label('Documento a subsanar')
                    ->limit(40),
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
                    ->label('Documento de subsanación')
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
            ->headerActions([
                CreateAction::make()
                    ->label('Solicitar subsanación'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
