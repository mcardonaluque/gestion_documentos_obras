<?php

namespace App\Filament\Resources\TablaDeMunicipios;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TablaDeMunicipios\Pages\ListTablaDeMunicipios;
use App\Filament\Resources\TablaDeMunicipios\Pages\CreateTablaDeMunicipio;
use App\Filament\Resources\TablaDeMunicipios\Pages\EditTablaDeMunicipio;
use App\Filament\Resources\TablaDeMunicipioResource\Pages;
use App\Filament\Resources\TablaDeMunicipioResource\RelationManagers;
use App\Models\TablaDeMunicipio;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TablaDeMunicipioResource extends Resource
{
    protected static ?string $model = TablaDeMunicipio::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_municipio')
                    ->maxLength(40),
                TextInput::make('cod_MAP')
                    ->numeric(),
                TextInput::make('cod_mapPPOS')
                    ->numeric(),
                TextInput::make('nombre_MAP')
                    ->maxLength(50),
                TextInput::make('comarca')
                    ->maxLength(1),
                TextInput::make('PJudicial')
                    ->maxLength(1),
                TextInput::make('numero_habitantes')
                    ->numeric(),
                TextInput::make('Porc_ayunta')
                    ->numeric(),
                TextInput::make('zona')
                    ->maxLength(1),
                TextInput::make('cp')
                    ->numeric(),
                TextInput::make('Alcalde')
                    ->maxLength(50),
                TextInput::make('LicenciaFiscal')
                    ->maxLength(15),
                TextInput::make('Secretario')
                    ->maxLength(40),
                TextInput::make('MunicipioAbreviado')
                    ->maxLength(15),
                TextInput::make('Cod_Mun_Alfa')
                    ->maxLength(3),
                TextInput::make('NIF')
                    ->maxLength(10),
                TextInput::make('usuario_ftp')
                    ->maxLength(30),
                TextInput::make('pw_ftp')
                    ->maxLength(10),
                TextInput::make('ip')
                    ->maxLength(15),
                TextInput::make('tipo')
                    ->maxLength(2),
                TextInput::make('direccion')
                    ->maxLength(250),
                TextInput::make('sede')
                    ->maxLength(250),
                TextInput::make('id')
                    ->label('ID')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('codigo_municipio')
            ->columns([
                TextColumn::make('codigo_municipio')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombre_municipio')
                    ->searchable(),
                TextColumn::make('cod_MAP')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cod_mapPPOS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombre_MAP')
                    ->searchable(),
                TextColumn::make('comarca')
                    ->searchable(),
                TextColumn::make('PJudicial')
                    ->searchable(),
                TextColumn::make('numero_habitantes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Porc_ayunta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('zona')
                    ->searchable(),
                TextColumn::make('cp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Alcalde')
                    ->searchable(),
                TextColumn::make('LicenciaFiscal')
                    ->searchable(),
                TextColumn::make('Secretario')
                    ->searchable(),
                TextColumn::make('MunicipioAbreviado')
                    ->searchable(),
                TextColumn::make('Cod_Mun_Alfa')
                    ->searchable(),
                TextColumn::make('NIF')
                    ->searchable(),
                TextColumn::make('usuario_ftp')
                    ->searchable(),
                TextColumn::make('pw_ftp')
                    ->searchable(),
                TextColumn::make('ip')
                    ->searchable(),
                TextColumn::make('tipo')
                    ->searchable(),
                TextColumn::make('direccion')
                    ->searchable(),
                TextColumn::make('sede')
                    ->searchable(),
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListTablaDeMunicipios::route('/'),
            'create' => CreateTablaDeMunicipio::route('/create'),
            'edit' => EditTablaDeMunicipio::route('/{record}/edit'),
        ];
    }
}
