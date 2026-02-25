<?php

namespace App\Filament\Shared\Resources\Expedientes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'documentos';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cod_plan')
                ->relationship('planes', 'codigo_plan')
                    ->label('Código plan')
                    ->disabled()
                    ->default(fn () => $this->getOwnerRecord()?->codigo_plan)
                    ->required()
                    ->maxLength(45),
                TextInput::make('referencia')
                    ->label('Número obra')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->referencia)
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->subreferencia)
                    ->numeric()
                    ->default(null),
                TextInput::make('ao_ejecucion')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->ao_ejecucion)
                    ->required()
                    ->numeric(),
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->expediente_id)
                    ->required(),
                DatePicker::make('fechaincorporacion')
                    ->default(now())
                    ->required(),
                DatePicker::make('fechaHelp'),
                Select::make('cod_documento')
                    ->relationship('tipodocumentos', 'nombre')
                    ->required()
                    ->numeric(),
                TextInput::make('csv')
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                TextInput::make('nsecuencia')
                    ->readOnly()
                    ->default(fn () => ((int) ($this->getOwnerRecord()?->documentos()?->max('nsecuencia') ?? 0)) + 1)
                    ->numeric()
                    ->default(null),
                Select::make('estado')
                    ->relationship('estados', 'nombre')
                    ->required()
                    ->numeric(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
               Select::make('destino')
                    ->relationship('destinos', 'destino')
                    ->label('Destino'),
                Select::make('procedencia')
                    ->relationship('procedencias', 'destino')
                    ->label('Procedencia'),
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
                TextColumn::make('cod_documento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('csv')
                    ->searchable(),
                TextColumn::make('nregistro')
                    ->searchable(),
                TextColumn::make('nsecuencia')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $ownerRecord = $this->getOwnerRecord();

                        $data['expediente_id'] = $ownerRecord?->expediente_id;
                        $data['cod_plan'] = $ownerRecord?->codigo_plan;
                        $data['referencia'] = $ownerRecord?->referencia;
                        $data['subreferencia'] = $ownerRecord?->subreferencia;
                        $data['ao_ejecucion'] = $ownerRecord?->ao_ejecucion;
                        $data['nsecuencia'] = ((int) ($ownerRecord?->documentos()?->max('nsecuencia') ?? 0)) + 1;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
