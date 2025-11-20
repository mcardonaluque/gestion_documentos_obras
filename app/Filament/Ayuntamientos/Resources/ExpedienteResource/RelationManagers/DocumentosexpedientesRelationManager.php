<?php

namespace App\Filament\Ayuntamientos\Resources\ExpedienteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentosexpedientesRelationManager extends RelationManager
{
    protected static string $relationship = 'documentosexpedientes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('expediente_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
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
            Forms\Components\TextInput::make('cod_dcoumento')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('expediente_id')
                ->required()
                ->maxLength(45),
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
