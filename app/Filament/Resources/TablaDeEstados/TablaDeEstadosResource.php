<?php

namespace App\Filament\Resources\TablaDeEstados;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TablaDeEstados\Pages\ListTablaDeEstados;
use App\Filament\Resources\TablaDeEstados\Pages\CreateTablaDeEstados;
use App\Filament\Resources\TablaDeEstados\Pages\EditTablaDeEstados;
use App\Filament\Resources\TablaDeEstadosResource\Pages;
use App\Filament\Resources\TablaDeEstadosResource\RelationManagers;
use App\Models\TablaDeEstados;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TablaDeEstadosResource extends Resource
{
    protected static ?string $model = TablaDeEstados::class;
    protected static bool $isScopedToTenant = false;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-check';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cod_estado')
                    ->required()
                    ->maxLength(3),
                TextInput::make('estado')
                    ->maxLength(40),
                TextInput::make('estado_abrev')
                    ->maxLength(20),
                TextInput::make('moduloini')
                    ->maxLength(15),
                TextInput::make('modulofin')
                    ->maxLength(15),
                TextInput::make('Tabla')
                    ->maxLength(20),
                Toggle::make('Planes')
                    ->required(),
                Toggle::make('SubvRP')
                    ->required(),
                Toggle::make('Contratacion')
                    ->required(),
                Toggle::make('Proyecto')
                    ->required(),
                Toggle::make('Obras')
                    ->required(),
                Toggle::make('Certificaciones')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_estado')
                    ->searchable(),
                TextColumn::make('estado')
                    ->searchable(),
                TextColumn::make('estado_abrev')
                    ->searchable(),
                TextColumn::make('moduloini')
                    ->searchable(),
                TextColumn::make('modulofin')
                    ->searchable(),
                TextColumn::make('Tabla')
                    ->searchable(),
                IconColumn::make('Planes')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('SubvRP')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Contratacion')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Proyecto')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Obras')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Certificaciones')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListTablaDeEstados::route('/'),
            'create' => CreateTablaDeEstados::route('/create'),
            'edit' => EditTablaDeEstados::route('/{record}/edit'),
        ];
    }
}
