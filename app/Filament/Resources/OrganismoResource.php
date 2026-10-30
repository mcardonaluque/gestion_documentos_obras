<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganismoResource\Pages;
use App\Models\Organismo;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Recurso Filament para la gestión del catálogo de organismos.
 *
 * Gestiona el modelo App\Models\Organismo en el panel de administración.
 * No define grupo de navegación propio y sus páginas CRUD viven en
 * OrganismoResource\Pages (archivo situado en la raíz de Resources).
 */
class OrganismoResource extends Resource
{
    protected static ?string $model = Organismo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'organismo';

    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('denominacion'),
                Forms\Components\TextInput::make('abreviatura'),
                Forms\Components\TextInput::make('TextoDecreto'),
                Forms\Components\TextInput::make('agrupacion'),
                Forms\Components\TextInput::make('orden')
                    ->numeric(),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('organismo')
            ->defaultSort('codigo_organismo')
            ->columns([
                Tables\Columns\TextColumn::make('codigo_organismo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('denominacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('abreviatura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('TextoDecreto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agrupacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orden')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** Declara los relation managers asociados al recurso. */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /** Declara las páginas y rutas del recurso. */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganismos::route('/'),
            'create' => Pages\CreateOrganismo::route('/create'),
            'edit' => Pages\EditOrganismo::route('/{record}/edit'),
        ];
    }
}
