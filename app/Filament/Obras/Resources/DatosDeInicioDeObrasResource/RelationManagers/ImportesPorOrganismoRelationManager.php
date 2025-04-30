<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImportesPorOrganismoRelationManager extends RelationManager
{
    protected static string $relationship = 'importesPorOrganismo';
    protected function getInverseRelationship(): string
    {
        return 'obra'; // esto debe ser el nombre de la relación inversa en el modelo ImportesporOrganismo
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Expediente')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('organismo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('Porc_imp_aprobado')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('importe_aprobado')
                    ->required()
                    ->maxLength(255),
                
            ]);
    }

    public function table(Table $table): Table
    {
        
        return $table
            ->recordTitleAttribute('Expediente')
            ->columns([
                Tables\Columns\TextColumn::make('organismo'),
                Tables\Columns\TextColumn::make('Porc_imp_aprobado'),
                Tables\Columns\TextColumn::make('importe_aprobado'),
                Tables\Columns\TextColumn::make('Porc_importe_contratar'),
                Tables\Columns\TextColumn::make('importe_a_contratar'),
                Tables\Columns\TextColumn::make('Porc_imp_adjudicado'),
                Tables\Columns\TextColumn::make('importe_adjudicacion'),
                Tables\Columns\TextColumn::make('Porc_imp_baja'),
                Tables\Columns\TextColumn::make('importe_baja_contratacion'),
                
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
