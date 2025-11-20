<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TablaDeMunicipioResource\Pages;
use App\Filament\Resources\TablaDeMunicipioResource\RelationManagers;
use App\Models\TablaDeMunicipio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TablaDeMunicipioResource extends Resource
{
    protected static ?string $model = TablaDeMunicipio::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre_municipio')
                    ->maxLength(40),
                Forms\Components\TextInput::make('cod_MAP')
                    ->numeric(),
                Forms\Components\TextInput::make('cod_mapPPOS')
                    ->numeric(),
                Forms\Components\TextInput::make('nombre_MAP')
                    ->maxLength(50),
                Forms\Components\TextInput::make('comarca')
                    ->maxLength(1),
                Forms\Components\TextInput::make('PJudicial')
                    ->maxLength(1),
                Forms\Components\TextInput::make('numero_habitantes')
                    ->numeric(),
                Forms\Components\TextInput::make('Porc_ayunta')
                    ->numeric(),
                Forms\Components\TextInput::make('zona')
                    ->maxLength(1),
                Forms\Components\TextInput::make('cp')
                    ->numeric(),
                Forms\Components\TextInput::make('Alcalde')
                    ->maxLength(50),
                Forms\Components\TextInput::make('LicenciaFiscal')
                    ->maxLength(15),
                Forms\Components\TextInput::make('Secretario')
                    ->maxLength(40),
                Forms\Components\TextInput::make('MunicipioAbreviado')
                    ->maxLength(15),
                Forms\Components\TextInput::make('Cod_Mun_Alfa')
                    ->maxLength(3),
                Forms\Components\TextInput::make('NIF')
                    ->maxLength(10),
                Forms\Components\TextInput::make('usuario_ftp')
                    ->maxLength(30),
                Forms\Components\TextInput::make('pw_ftp')
                    ->maxLength(10),
                Forms\Components\TextInput::make('ip')
                    ->maxLength(15),
                Forms\Components\TextInput::make('tipo')
                    ->maxLength(2),
                Forms\Components\TextInput::make('direccion')
                    ->maxLength(250),
                Forms\Components\TextInput::make('sede')
                    ->maxLength(250),
                Forms\Components\TextInput::make('id')
                    ->label('ID')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_municipio')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_municipio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cod_MAP')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cod_mapPPOS')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_MAP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comarca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('PJudicial')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_habitantes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Porc_ayunta')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zona')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Alcalde')
                    ->searchable(),
                Tables\Columns\TextColumn::make('LicenciaFiscal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Secretario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MunicipioAbreviado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Cod_Mun_Alfa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('NIF')
                    ->searchable(),
                Tables\Columns\TextColumn::make('usuario_ftp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pw_ftp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sede')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
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
            'index' => Pages\ListTablaDeMunicipios::route('/'),
            'create' => Pages\CreateTablaDeMunicipio::route('/create'),
            'edit' => Pages\EditTablaDeMunicipio::route('/{record}/edit'),
        ];
    }
}
