<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\AyudaTecnica;

class AyudaRelationManager extends RelationManager
{
    protected static string $relationship = 'ayuda';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Expediente')
                    //->required()
                    ->fillUsing(function ($component, $state, $record) {
                        $component->state($this->ownerRecord->expediente);
                    })
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\Select::make('dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayudaR', 'DENOMINACION'),

                Forms\Components\Select::make('departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayudaD', 'DENOMINACION'),

                Forms\Components\TextInput::make('AyuTecRed')
                    ->required()
                    //->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('AyuTecDir')
                    ->required()
                    //->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('SubvencionEconomicaR')
                    //->required()
                    //->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('SubvencionEconomicaD')
                    //->required()
                    //->disabled()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Expediente')
            ->columns([
                Tables\Columns\TextColumn::make('Expediente'),
                Tables\Columns\TextColumn::make('dpto_redactor'),
                Tables\Columns\TextColumn::make('departamento_direcccion'),
                Tables\Columns\TextColumn::make('AyuTecRed'),
                Tables\Columns\TextColumn::make('AyuTecDir'),
                Tables\Columns\TextColumn::make('SubvencionEconomicaR'),
                Tables\Columns\TextColumn::make('SubvencionEconomicaD'),
                Tables\Columns\TextColumn::make('codigo_municipio'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->visible(fn() =>!AyudaTecnica::exists()),
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
