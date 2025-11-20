<?php

namespace App\Filament\Widgets;

use App\Models\Expediente;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ExpedientesTable extends BaseWidget
{
    public ?string $expedienteSeleccionado = null;
    protected static ?string $heading = 'Expedientes';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {      
        return $table
     
            ->query(Expediente::query()->where('team_id', filament()->getTenant()->id))
            ->columns([
                Tables\Columns\TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Expediente $record) => $record->estados->estado_abrev ?? 'Sin estado'),
                Tables\Columns\TextColumn::make('nombre_obra')
                    ->label('Obra')
                    ->searchable()
                    ->description(fn (Expediente $record) => $record->documentos->count() . ' Documentos' ?? 'Sin documentos')
                    ->sortable(),
                Tables\Columns\TextColumn::make('municipios.nombre_municipio')
                    ->label('Ubicación')
                    ->searchable()
                    ->sortable(),
              /*  Tables\Columns\TextColumn::make('documentos_count')
                    ->label('Nº Documentos')
                    ->counts('documentos')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),*/
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('seleccionar')
                    ->icon('heroicon-o-eye')
                    ->action(function (Expediente $record) {
                        $this->expedienteSeleccionado = $record->expediente_id;
                        // Emitir evento para el otro widget
                        $this->dispatch('expedienteSeleccionado', expedienteId: $record->expediente_id);
                    })
                    ->color('primary')
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