<?php

namespace App\Filament\Widgets;

use App\Filament\Traits\CommonFilters;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use App\Models\Expediente;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class ExpedientesTable extends BaseWidget
{
    use CommonFilters;

    public ?string $expedienteSeleccionado = null;
    protected static ?string $heading = 'Expedientes';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $query = Expediente::query()
            ->with(['estados', 'documentos', 'municipios']);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user?->hasGlobalAyuntamientosAccess()) {
            $query->where('team_id', filament()->getTenant()->id);
        }

        return $table
            ->query($query)
            ->defaultSort('ao_ejecucion', 'desc')
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Expediente $record) => $record->estados->estado_abrev ?? 'Sin estado'),
                TextColumn::make('nombre_obra')
                    ->label('Obra')
                    ->searchable()
                    ->description(fn (Expediente $record) => $record->documentos->count() . ' Documentos' ?? 'Sin documentos')
                    ->sortable(),
                TextColumn::make('municipios.nombre_municipio')
                    ->label('Ubicación')
                    ->searchable()
                    ->sortable(),
              /*  Tables\Columns\TextColumn::make('documentos_count')
                    ->label('Nº Documentos')
                    ->counts('documentos')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),*/

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters(self::getCommonFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->deferFilters(false)
            ->recordActions([
                Action::make('seleccionar')
                    ->label(function (Expediente $record){
                        //dd($record);
                        //dd($this->obraSeleccionadaId === $record->Expediente );

                        return $this->expedienteSeleccionado === $record->expediente_id
                            ? 'Seleccionada'
                            : 'Seleccionar';
                    })


                    ->icon(fn (Expediente $record) =>
                        $this->expedienteSeleccionado === $record->expediente_id
                            ? 'heroicon-o-check-circle'
                            : 'heroicon-o-plus-circle'
                    )
                    ->color(fn (Expediente $record) =>
                        $this->expedienteSeleccionado === $record->expediente_id
                            ? 'success'
                            : 'primary'
                    )
                    ->action(function (Expediente $record) {
                        $this->expedienteSeleccionado = $record->expediente_id;
                        // Emitir evento para el otro widget
                        $this->dispatch('expedienteSeleccionado', expedienteId: $record->expediente_id);
                    })

                    ->extraAttributes(function (Expediente $record) {
                        return [
                            'class' => $this->expedienteSeleccionado === $record->expediente_id
                                ? 'bg-blue-100'
                                : '',
                        ];
                    }),
            ])
            ->recordAction('seleccionar')
            ->recordUrl(null);
    }
}
