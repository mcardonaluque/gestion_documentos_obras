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

    protected static string | \UnitEnum | null $navigationGroup = 'Catálogos';

    protected static ?string $navigationLabel = 'Estados';

    protected static ?string $modelLabel = 'estado';

    protected static ?string $pluralModelLabel = 'estados';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cod_estado')
                    ->label('Código')
                    ->required()
                    ->maxLength(3),
                TextInput::make('estado')
                    ->label('Descripción')
                    ->required()
                    ->maxLength(40),
                TextInput::make('estado_abrev')
                    ->label('Abreviatura')
                    ->maxLength(20),
                TextInput::make('moduloini')
                    ->label('Módulo inicial')
                    ->maxLength(15),
                TextInput::make('modulofin')
                    ->label('Módulo final')
                    ->maxLength(15),
                TextInput::make('Tabla')
                    ->label('Tabla')
                    ->maxLength(20),
                Toggle::make('Planes')
                    ->label('Planes')
                    ->required(),
                Toggle::make('SubvRP')
                    ->label('Subv. RP')
                    ->required(),
                Toggle::make('Contratacion')
                    ->label('Contratación')
                    ->required(),
                Toggle::make('Proyecto')
                    ->label('Proyecto')
                    ->required(),
                Toggle::make('Obras')
                    ->label('Obras')
                    ->required(),
                Toggle::make('Certificaciones')
                    ->label('Certificaciones')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_estado')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('estado')
                    ->label('Descripción')
                    ->searchable(),
                TextColumn::make('estado_abrev')
                    ->label('Abreviatura')
                    ->searchable(),
                TextColumn::make('moduloini')
                    ->label('Módulo inicial')
                    ->searchable(),
                TextColumn::make('modulofin')
                    ->label('Módulo final')
                    ->searchable(),
                TextColumn::make('Tabla')
                    ->label('Tabla')
                    ->searchable(),
                IconColumn::make('Planes')
                    ->label('Planes')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('SubvRP')
                    ->label('Subv. RP')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Contratacion')
                    ->label('Contratación')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Proyecto')
                    ->label('Proyecto')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Obras')
                    ->label('Obras')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Certificaciones')
                    ->label('Certificaciones')
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
