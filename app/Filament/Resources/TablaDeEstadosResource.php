<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TablaDeEstadosResource\Pages;
use App\Filament\Resources\TablaDeEstadosResource\RelationManagers;
use App\Models\TablaDeEstados;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TablaDeEstadosResource extends Resource
{
    protected static ?string $model = TablaDeEstados::class;
    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_estado')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('estado')
                    ->maxLength(40),
                Forms\Components\TextInput::make('estado_abrev')
                    ->maxLength(20),
                Forms\Components\TextInput::make('moduloini')
                    ->maxLength(15),
                Forms\Components\TextInput::make('modulofin')
                    ->maxLength(15),
                Forms\Components\TextInput::make('Tabla')
                    ->maxLength(20),
                Forms\Components\Toggle::make('Planes')
                    ->required(),
                Forms\Components\Toggle::make('SubvRP')
                    ->required(),
                Forms\Components\Toggle::make('Contratacion')
                    ->required(),
                Forms\Components\Toggle::make('Proyecto')
                    ->required(),
                Forms\Components\Toggle::make('Obras')
                    ->required(),
                Forms\Components\Toggle::make('Certificaciones')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_abrev')
                    ->searchable(),
                Tables\Columns\TextColumn::make('moduloini')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modulofin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Tabla')
                    ->searchable(),
                Tables\Columns\IconColumn::make('Planes')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('SubvRP')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('Contratacion')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('Proyecto')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('Obras')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('Certificaciones')
                    ->boolean()
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
            'index' => Pages\ListTablaDeEstados::route('/'),
            'create' => Pages\CreateTablaDeEstados::route('/create'),
            'edit' => Pages\EditTablaDeEstados::route('/{record}/edit'),
        ];
    }
}
