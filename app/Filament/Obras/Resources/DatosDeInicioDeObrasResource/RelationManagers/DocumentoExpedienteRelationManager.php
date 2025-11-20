<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers;

use App\Models\Expediente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoExpedienteRelationManager extends RelationManager
{
    protected static string $relationship = 'documentos';
    protected static ?string $title = 'Documentos del Expediente Seleccionado';

    protected static ?string $label = 'Documento';

    protected static ?string $pluralLabel = 'Documentos';

    // Sobrescribir para usar el expediente seleccionado
    public function getOwnerRecord(): Expediente
    {
        $ownerRecord = $this->getLivewire()->getSelectedRecord();
        
        if (!$ownerRecord) {
            // Crear un expediente vacío para evitar errores
            return new Expediente();
        }

        return $ownerRecord;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_plan')
                ->required()
                ->maxLength(45),
            Forms\Components\TextInput::make('referencia')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('subreferencia')
                ->numeric()
                ->default(null),
            Forms\Components\TextInput::make('ao_ejecucion')
                ->required()
                ->numeric(),
            Forms\Components\DatePicker::make('fechaincorporacion')
                ->required(),
            Forms\Components\DatePicker::make('fechaHelp'),
            Forms\Components\TextInput::make('coddcoumento')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('csv')
                ->maxLength(50)
                ->default(null),
            Forms\Components\TextInput::make('nregistro')
                ->maxLength(45)
                ->default(null),
            Forms\Components\TextInput::make('nsecuencia')
                ->numeric()
                ->default(null),
            Forms\Components\TextInput::make('estado')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('descripcion')
                ->maxLength(255)
                ->default(null),
            Forms\Components\TextInput::make('destino')
                ->numeric(),
            Forms\Components\TextInput::make('procedencia')
                ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                Tables\Columns\TextColumn::make('cod_plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fechaHelp')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coddcoumento')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expediente_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('csv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nregistro')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Agregar Documento')
                ->visible(fn () => $this->getLivewire()->expedienteSeleccionado !== null),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(fn () => $this->getLivewire()->expedienteSeleccionado 
                ? 'No hay documentos en este expediente' 
                : 'Selecciona un expediente'
            )
            ->emptyStateDescription(fn () => $this->getLivewire()->expedienteSeleccionado
                ? 'Agrega el primer documento'
                : 'Haz click en un expediente de la tabla superior'
            )
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar Documento')
                    ->visible(fn () => $this->getLivewire()->expedienteSeleccionado !== null),
            ]);
    }
}
