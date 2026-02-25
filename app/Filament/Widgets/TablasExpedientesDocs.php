<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Models\Expediente;
use App\Models\DocumentoExpediente;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TablasExpedientesDocs extends BaseWidget
{
    protected static ?string $heading = 'Expedientes y Documentos';

    public ?string $expedienteSeleccionado = null;

    public function expedientesTable(Table $table): Table
    {
        return $table
            ->query(Expediente::query())
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Expediente $record) => $record->estados->descripcion ?? 'Sin estado'),
                
                TextColumn::make('documentos_count')
                    ->label('Nº Documentos')
                    ->counts('documentos')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
                    
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('seleccionar')
                    ->icon('heroicon-o-eye')
                    ->action(function (Expediente $record) {
                        $this->expedienteSeleccionado = $record->expediente_id;
                    })
                    ->color('primary'),
            ])
            ->recordAction('seleccionar')
            ->recordUrl(null);
    }

    public function documentosTable(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->expedienteSeleccionado) {
                    // No mostrar nada si no hay expediente seleccionado
                    return DocumentoExpediente::where('id', 0);
                }
                
                return DocumentoExpediente::where('Expediente', $this->expedienteSeleccionado);
            })
            ->columns([
                TextColumn::make('cod_documento')
                    ->label('Código')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('tipodocumentos.descripcion')
                    ->label('Tipo Documento')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50)
                    ->searchable(),
                    
                TextColumn::make('fechaincorporacion')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completado', 'aprobado' => 'success',
                        'pendiente' => 'warning',
                        'rechazado' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nuevo Documento')
                    ->model(DocumentoExpediente::class)
                    ->schema([
                        CreateAction::makeForm([
                            TextInput::make('cod_documento')
                                ->label('Código Documento')
                                ->required(),
                                
                            Select::make('cod_documento')
                                ->label('Tipo Documento')
                                ->relationship('tipodocumentos', 'descripcion')
                                ->searchable()
                                ->preload(),
                                
                            Textarea::make('descripcion')
                                ->label('Descripción'),
                                
                            DatePicker::make('fechaincorporacion')
                                ->label('Fecha Incorporación'),
                                
                            Select::make('estado')
                                ->label('Estado')
                                ->options([
                                    'pendiente' => 'Pendiente',
                                    'completado' => 'Completado',
                                    'aprobado' => 'Aprobado',
                                    'rechazado' => 'Rechazado',
                                ]),
                        ])
                    ])
                    ->action(function (array $data) {
                        // Asignar automáticamente el expediente seleccionado
                        $data['Expediente'] = $this->expedienteSeleccionado;
                        DocumentoExpediente::create($data);
                    })
                    ->disabled(fn () => !$this->expedienteSeleccionado),
            ])
            ->recordActions([
                EditAction::make()
                    ->model(DocumentoExpediente::class)
                    ->schema([
                        TextInput::make('cod_documento')
                            ->label('Código Documento')
                            ->required(),
                            
                        Select::make('cod_documento')
                            ->label('Tipo Documento')
                            ->relationship('tipodocumentos', 'descripcion')
                            ->searchable()
                            ->preload(),
                            
                        Textarea::make('descripcion')
                            ->label('Descripción'),
                            
                        DatePicker::make('fechaincorporacion')
                            ->label('Fecha Incorporación'),
                            
                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'completado' => 'Completado',
                                'aprobado' => 'Aprobado',
                                'rechazado' => 'Rechazado',
                            ]),
                    ]),
                DeleteAction::make(),
            ])
            ->recordAction('renderizar')
            ->emptyStateHeading($this->expedienteSeleccionado ? 'No hay documentos' : 'Selecciona un expediente')
            ->emptyStateDescription($this->expedienteSeleccionado ? 'Agrega el primer documento' : 'Haz click en un expediente')
            ->emptyStateIcon('heroicon-o-document');
    }

    public function getTable(): Table
    {
      
        return $this->expedientesTable(Table::make());
    }

    public function renderizar()
    {
        return $this->render();
    }
}