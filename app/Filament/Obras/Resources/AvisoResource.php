<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\AvisoResource\Pages;
use App\Filament\Obras\Resources\AvisoResource\RelationManagers;
use App\Models\Aviso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvisoResource extends Resource
{
    protected static ?string $model = Aviso::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Referencia')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('TipoAviso')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('Codigo_Plan')
                    ->maxLength(7),
                Forms\Components\TextInput::make('numero_obra')
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->numeric(),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->numeric(),
                Forms\Components\TextInput::make('Usuario')
                    ->maxLength(20),
                Forms\Components\DateTimePicker::make('FecSolucion'),
                Forms\Components\Toggle::make('borrado')
                    ->required(),
                Forms\Components\TextInput::make('Expediente')
                    ->maxLength(100),
                Forms\Components\TextInput::make('team_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Referencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('TipoAviso')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Codigo_Plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_obra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Usuario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('FecSolucion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('borrado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Expediente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListAvisos::route('/'),
            'create' => Pages\CreateAviso::route('/create'),
            'edit' => Pages\EditAviso::route('/{record}/edit'),
        ];
    }
}
