<?php

namespace App\Filament\Widgets;

use App\Filament\Obras\Resources\DocumentoexpedienteResource;
use App\Models\DocumentoExpediente;
use Illuminate\Support\Str;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class DocumentosTable extends BaseWidget
{
    public ?string $expedienteSeleccionado = null;
    public ?string $documentoSeleccionado = null;
    protected static ?string $heading = 'Documentos del Expediente';
    protected int | string | array $columnSpan = 'full';

    // Escuchar el evento del otro widget
    #[On('expedienteSeleccionado')]
    public function actualizarExpediente($expedienteId): void
    {
        $this->expedienteSeleccionado = $expedienteId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->expedienteSeleccionado) {
                    return DocumentoExpediente::where('idDocumento','0'); // Query vacío
                }
               //dd(DocumentoExpediente::where('expediente_id', $this->expedienteSeleccionado));
                return DocumentoExpediente::where('expediente_id', $this->expedienteSeleccionado);
            })
            ->columns([
                Tables\Columns\TextColumn::make('idDocumento')
                    ->label('Id Documento')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('tipodocumentos.nombre')
                    ->label('Tipo Documento')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('fechaincorporacion')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('estado')
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
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Documento')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('cod_documento')
                            ->label('Código Documento')
                            ->required(),
                            
                        \Filament\Forms\Components\Select::make('cod_documento')
                            ->label('Tipo Documento')
                            ->relationship('tipodocumentos', 'descripcion')
                            ->searchable()
                            ->preload(),
                            
                        \Filament\Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción'),
                            
                        \Filament\Forms\Components\DatePicker::make('fechaincorporacion')
                            ->label('Fecha Incorporación'),
                            
                        \Filament\Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'completado' => 'Completado',
                                'aprobado' => 'Aprobado',
                                'rechazado' => 'Rechazado',
                            ]),
                    ])
                    ->action(function (array $data) {
                        // Asignar automáticamente el expediente seleccionado
                        $data['expediente_id'] = $this->expedienteSeleccionado;
                        DocumentoExpediente::create($data);
                    })
                    ->disabled(fn () => !$this->expedienteSeleccionado),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        \Filament\Forms\Components\TextInput::make('cod_documento')
                            ->label('Código Documento')
                            ->required(),
                            
                        \Filament\Forms\Components\Select::make('documento')
                            ->label('Tipo Documento')
                            ->relationship('tipodocumentos', 'nombre')
                            ->searchable()
                            ->preload(),
                            
                        \Filament\Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción'),
                            
                        \Filament\Forms\Components\DatePicker::make('fechaincorporacion')
                            ->label('Fecha Incorporación'),
                            
                        \Filament\Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'completado' => 'Completado',
                                'aprobado' => 'Aprobado',
                                'rechazado' => 'Rechazado',
                            ]),
                    ]),
                Tables\Actions\ViewAction::make('Ver Documento')
                
                ->label('Documentos')
                ->icon('heroicon-o-folder-open')
                ->modalWidth('x1') // opcional: ancho del modal
                ->modalHeading(fn ($record) => "Documento {$record->descripcion} del expediente {$record->expediente_id}")
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Cerrar')
                ->modalContent(function ($record) {
                return \Filament\Infolists\Infolist::make()
                ->record($record)
                    ->schema([
                    \Filament\Infolists\Components\Section::make('Información del Documento')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('idDocumento')
                            ->label('ID Documento'),
                        \Filament\Infolists\Components\TextEntry::make('cod_documento')
                            ->label('Código Documento'),
                        \Filament\Infolists\Components\TextEntry::make('tipodocumentos.nombre')
                            ->label('Tipo Documento'),
                    ])
                    ->columns(3),
                    
                \Filament\Infolists\Components\Section::make('Contenido')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('descripcion')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->html(), // Si contiene HTML
                    ]),
                    
                \Filament\Infolists\Components\Section::make('Fechas y Estado')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('fechaincorporacion')
                            ->label('Fecha Incorporación')
                            ->date('d/m/Y'),
                        \Filament\Infolists\Components\TextEntry::make('fechaHelp')
                            ->label('Fecha Help')
                            ->date('d/m/Y'),
                        \Filament\Infolists\Components\TextEntry::make('ao_ejecucion')
                            ->label('Año Ejecución'),
                        \Filament\Infolists\Components\TextEntry::make('estados.nombre')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'completado', 'aprobado' => 'success',
                                'pendiente' => 'warning',
                                'rechazado' => 'danger',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),
                    
                \Filament\Infolists\Components\Section::make('Referencias')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('referencia')
                            ->label('Referencia'),
                        \Filament\Infolists\Components\TextEntry::make('subreferencia')
                            ->label('Subreferencia'),
                        \Filament\Infolists\Components\TextEntry::make('nregistro')
                            ->label('Nº Registro'),
                       /* \Filament\Infolists\Components\TextEntry::make('nsecuencia')
                            ->label('Nº Secuencia'),*/
                    ])
                    ->columns(2)
                    ->collapsible(),
                    
                \Filament\Infolists\Components\Section::make('Información Adicional')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('csv')
                            ->label('CSV'),
                        \Filament\Infolists\Components\TextEntry::make('notificado')
                            ->label('Notificado')
                            ->badge()
                            ->color(fn ($state): string => $state ? 'success' : 'gray')
                            ->formatStateUsing(fn ($state): string => $state ? 'Sí' : 'No'),
                       
                    ])
                    ->columns(3)
                    ->collapsible(),
                    
                \Filament\Infolists\Components\Section::make('Relaciones')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('expediente_id')
                            ->label('Expediente'),
                        \Filament\Infolists\Components\TextEntry::make('destinos.destino')
                            ->label('Destino'),
                        \Filament\Infolists\Components\TextEntry::make('procedencias.destino')
                            ->label('Procedencia'),
                    ])
                    ->columns(3)
                    ->collapsible(),
                    ]);
        })
            ])
            ->emptyStateHeading($this->expedienteSeleccionado ? 'No hay documentos' : 'Selecciona un expediente')
            ->emptyStateDescription($this->expedienteSeleccionado ? 'Agrega el primer documento' : 'Haz click en un expediente de la tabla superior')
            ->emptyStateIcon('heroicon-o-document');
    }
}