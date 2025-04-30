<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpedientesResource\Pages;
use App\Filament\Resources\ExpedientesResource\RelationManagers;
use App\Models\Expediente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpedientesResource extends Resource
{
    protected static ?string $model = Expediente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup="Documentación";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero_exp')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('codigo_plan')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->label('Año de ejecución')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nombre_obra')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('cod_estado')
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('cod_fase')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('cod_estado_help')
                    ->required()
                    ->maxLength(510),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_exp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_obra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cod_estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cod_fase')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cod_estado_help')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpedientes::route('/'),
            'create' => Pages\CreateExpedientes::route('/create'),
            'edit' => Pages\EditExpedientes::route('/{record}/edit'),
        ];
    }
}
