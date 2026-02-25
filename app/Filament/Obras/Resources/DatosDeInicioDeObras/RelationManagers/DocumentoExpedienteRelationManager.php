<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\Expediente;
use Filament\Forms;
use Filament\Schemas\Schema;
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
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cod_plan')
                ->required()
                ->maxLength(45),
            TextInput::make('referencia')
                ->required()
                ->numeric(),
            TextInput::make('subreferencia')
                ->numeric()
                ->default(null),
            TextInput::make('ao_ejecucion')
                ->required()
                ->numeric(),
            DatePicker::make('fechaincorporacion')
                ->required(),
            DatePicker::make('fechaHelp'),
            TextInput::make('coddcoumento')
                ->required()
                ->numeric(),
            TextInput::make('csv')
                ->maxLength(50)
                ->default(null),
            TextInput::make('nregistro')
                ->maxLength(45)
                ->default(null),
            TextInput::make('nsecuencia')
                ->numeric()
                ->default(null),
            TextInput::make('estado')
                ->required()
                ->numeric(),
            TextInput::make('descripcion')
                ->maxLength(255)
                ->default(null),
            TextInput::make('destino')
                ->numeric(),
            TextInput::make('procedencia')
                ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('cod_plan')
                    ->searchable(),
                TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('fechaHelp')
                    ->date()
                    ->sortable(),
                TextColumn::make('coddcoumento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('csv')
                    ->searchable(),
                TextColumn::make('nregistro')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                ->label('Agregar Documento')
                ->visible(fn () => $this->getLivewire()->expedienteSeleccionado !== null),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
                CreateAction::make()
                    ->label('Agregar Documento')
                    ->visible(fn () => $this->getLivewire()->expedienteSeleccionado !== null),
            ]);
    }
}
