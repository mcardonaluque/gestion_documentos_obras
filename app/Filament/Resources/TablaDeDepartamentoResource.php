<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TablaDeDepartamentoResource\Pages;
use App\Filament\Resources\TablaDeDepartamentoResource\RelationManagers;
use App\Models\TablaDeDepartamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TablaDeDepartamentoResource extends Resource
{
    protected static ?string $model = TablaDeDepartamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('DENOMINACION')
                    ->maxLength(100),
                Forms\Components\TextInput::make('Denominacion_Abreviada')
                    ->maxLength(70),
                Forms\Components\TextInput::make('ARTICULO')
                    ->maxLength(3),
                Forms\Components\TextInput::make('Tipo')
                    ->maxLength(1),
                Forms\Components\TextInput::make('JefeDpto')
                    ->maxLength(50),
                Forms\Components\TextInput::make('Sexo_JefeDpto')
                    ->maxLength(1),
                Forms\Components\TextInput::make('JefeDpto_Adjunto')
                    ->maxLength(50),
                Forms\Components\TextInput::make('Sexo_Adjunto')
                    ->maxLength(1),
                Forms\Components\TextInput::make('NumPropuesta')
                    ->numeric(),
                Forms\Components\TextInput::make('BDLOCAL')
                    ->maxLength(1),
                Forms\Components\TextInput::make('Organico')
                    ->numeric(),
                Forms\Components\TextInput::make('Jefatura')
                    ->maxLength(50),
                Forms\Components\TextInput::make('JefaturaCompleta')
                    ->maxLength(200),
                Forms\Components\TextInput::make('Jefatura_adjunta')
                    ->maxLength(50),
                Forms\Components\TextInput::make('Codigo_dpto_Antiguo')
                    ->numeric(),
                Forms\Components\TextInput::make('DENOMINACION_Antigua')
                    ->maxLength(70),
                Forms\Components\TextInput::make('DENOMINACION_ABREV')
                    ->maxLength(50),
                Forms\Components\Toggle::make('Cabecera')
                    ->required(),
                Forms\Components\TextInput::make('RutaDocGen')
                    ->maxLength(100),
                Forms\Components\Toggle::make('GestionaPlanes')
                    ->required(),
                Forms\Components\Toggle::make('GestionaSubvRP')
                    ->required(),
                Forms\Components\Toggle::make('ContrataExp')
                    ->required(),
                Forms\Components\Toggle::make('RedDirProyectos')
                    ->required(),
                Forms\Components\TextInput::make('Deno_abrev_listados')
                    ->maxLength(70),
                Forms\Components\TextInput::make('gendiputado')
                    ->maxLength(50),
                Forms\Components\TextInput::make('GestionaComInf')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('CODIGO_DPTO')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('DENOMINACION')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Denominacion_Abreviada')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ARTICULO')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('JefeDpto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Sexo_JefeDpto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('JefeDpto_Adjunto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Sexo_Adjunto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('NumPropuesta')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('BDLOCAL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Organico')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Jefatura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('JefaturaCompleta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Jefatura_adjunta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Codigo_dpto_Antiguo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('DENOMINACION_Antigua')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DENOMINACION_ABREV')
                    ->searchable(),
                Tables\Columns\IconColumn::make('Cabecera')
                    ->boolean(),
                Tables\Columns\TextColumn::make('RutaDocGen')
                    ->searchable(),
                Tables\Columns\IconColumn::make('GestionaPlanes')
                    ->boolean(),
                Tables\Columns\IconColumn::make('GestionaSubvRP')
                    ->boolean(),
                Tables\Columns\IconColumn::make('ContrataExp')
                    ->boolean(),
                Tables\Columns\IconColumn::make('RedDirProyectos')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Deno_abrev_listados')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gendiputado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('GestionaComInf')
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
            'index' => Pages\ListTablaDeDepartamentos::route('/'),
            'create' => Pages\CreateTablaDeDepartamento::route('/create'),
            'edit' => Pages\EditTablaDeDepartamento::route('/{record}/edit'),
        ];
    }
}
