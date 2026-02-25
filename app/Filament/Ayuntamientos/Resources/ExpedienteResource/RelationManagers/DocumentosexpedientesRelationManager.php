<?php

namespace App\Filament\Ayuntamientos\Resources\ExpedienteResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            TextInput::make('cod_dcoumento')
                ->required()
                ->numeric(),
            TextInput::make('expediente_id')
                ->required()
                ->maxLength(45),
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
