<?php

namespace App\Filament\Ayuntamientos\Resources\ExpedienteResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentosexpedientesRelationManager extends RelationManager
{
    protected static string $relationship = 'documentosexpedientes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('expediente_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('Codigo_plan')
                    ->searchable(),
                TextColumn::make('referencia')
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->sortable(),
                TextColumn::make('tipodocumentos.nombre')
                    ->label('Documento')
                    ->sortable(),
                TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado')
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
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
