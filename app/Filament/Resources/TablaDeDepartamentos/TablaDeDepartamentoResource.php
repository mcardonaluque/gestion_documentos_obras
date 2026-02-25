<?php

namespace App\Filament\Resources\TablaDeDepartamentos;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TablaDeDepartamentos\Pages\ListTablaDeDepartamentos;
use App\Filament\Resources\TablaDeDepartamentos\Pages\CreateTablaDeDepartamento;
use App\Filament\Resources\TablaDeDepartamentos\Pages\EditTablaDeDepartamento;
use App\Filament\Resources\TablaDeDepartamentoResource\Pages;
use App\Filament\Resources\TablaDeDepartamentoResource\RelationManagers;
use App\Models\TablaDeDepartamento;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TablaDeDepartamentoResource extends Resource
{
    protected static ?string $model = TablaDeDepartamento::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('DENOMINACION')
                    ->maxLength(100),
                TextInput::make('Denominacion_Abreviada')
                    ->maxLength(70),
                TextInput::make('ARTICULO')
                    ->maxLength(3),
                TextInput::make('Tipo')
                    ->maxLength(1),
                TextInput::make('JefeDpto')
                    ->maxLength(50),
                TextInput::make('Sexo_JefeDpto')
                    ->maxLength(1),
                TextInput::make('JefeDpto_Adjunto')
                    ->maxLength(50),
                TextInput::make('Sexo_Adjunto')
                    ->maxLength(1),
                TextInput::make('NumPropuesta')
                    ->numeric(),
                TextInput::make('BDLOCAL')
                    ->maxLength(1),
                TextInput::make('Organico')
                    ->numeric(),
                TextInput::make('Jefatura')
                    ->maxLength(50),
                TextInput::make('JefaturaCompleta')
                    ->maxLength(200),
                TextInput::make('Jefatura_adjunta')
                    ->maxLength(50),
                TextInput::make('Codigo_dpto_Antiguo')
                    ->numeric(),
                TextInput::make('DENOMINACION_Antigua')
                    ->maxLength(70),
                TextInput::make('DENOMINACION_ABREV')
                    ->maxLength(50),
                Toggle::make('Cabecera')
                    ->required(),
                TextInput::make('RutaDocGen')
                    ->maxLength(100),
                Toggle::make('GestionaPlanes')
                    ->required(),
                Toggle::make('GestionaSubvRP')
                    ->required(),
                Toggle::make('ContrataExp')
                    ->required(),
                Toggle::make('RedDirProyectos')
                    ->required(),
                TextInput::make('Deno_abrev_listados')
                    ->maxLength(70),
                TextInput::make('gendiputado')
                    ->maxLength(50),
                TextInput::make('GestionaComInf')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('CODIGO_DPTO')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('DENOMINACION')
                    ->searchable(),
                TextColumn::make('Denominacion_Abreviada')
                    ->searchable(),
                TextColumn::make('ARTICULO')
                    ->searchable(),
                TextColumn::make('Tipo')
                    ->searchable(),
                TextColumn::make('JefeDpto')
                    ->searchable(),
                TextColumn::make('Sexo_JefeDpto')
                    ->searchable(),
                TextColumn::make('JefeDpto_Adjunto')
                    ->searchable(),
                TextColumn::make('Sexo_Adjunto')
                    ->searchable(),
                TextColumn::make('NumPropuesta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('BDLOCAL')
                    ->searchable(),
                TextColumn::make('Organico')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Jefatura')
                    ->searchable(),
                TextColumn::make('JefaturaCompleta')
                    ->searchable(),
                TextColumn::make('Jefatura_adjunta')
                    ->searchable(),
                TextColumn::make('Codigo_dpto_Antiguo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('DENOMINACION_Antigua')
                    ->searchable(),
                TextColumn::make('DENOMINACION_ABREV')
                    ->searchable(),
                IconColumn::make('Cabecera')
                    ->boolean(),
                TextColumn::make('RutaDocGen')
                    ->searchable(),
                IconColumn::make('GestionaPlanes')
                    ->boolean(),
                IconColumn::make('GestionaSubvRP')
                    ->boolean(),
                IconColumn::make('ContrataExp')
                    ->boolean(),
                IconColumn::make('RedDirProyectos')
                    ->boolean(),
                TextColumn::make('Deno_abrev_listados')
                    ->searchable(),
                TextColumn::make('gendiputado')
                    ->searchable(),
                TextColumn::make('GestionaComInf')
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
            'index' => ListTablaDeDepartamentos::route('/'),
            'create' => CreateTablaDeDepartamento::route('/create'),
            'edit' => EditTablaDeDepartamento::route('/{record}/edit'),
        ];
    }
}
