<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\AlertaResource\Pages;
use App\Filament\Obras\Resources\AlertaResource\RelationManagers;
use App\Models\Alerta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlertaResource extends Resource
{
    protected static ?string $model = Alerta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('descripcion')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('tabla1')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('tabla2')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('campotabla1')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('campotabla2')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('condicion')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('mensaje')
                    ->required()
                    ->maxLength(510),
                Forms\Components\Toggle::make('activa')
                    ->required(),
                Forms\Components\TextInput::make('operacion')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('tipo')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('fase')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('estado')
                    ->maxLength(3),
                Forms\Components\TextInput::make('accion')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('plazo')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unidad_plazo')
                    ->required()
                    ->maxLength(2),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tabla1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tabla2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('campotabla1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('campotabla2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('condicion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mensaje')
                    ->searchable(),
                Tables\Columns\IconColumn::make('activa')
                    ->boolean(),
                Tables\Columns\TextColumn::make('operacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fase')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('plazo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidad_plazo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListAlertas::route('/'),
            'create' => Pages\CreateAlerta::route('/create'),
            'edit' => Pages\EditAlerta::route('/{record}/edit'),
        ];
    }
}
