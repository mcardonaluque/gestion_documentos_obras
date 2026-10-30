<?php

namespace App\Filament\Resources\TipoContratistas;

use App\Models\TipoContratista;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Recurso Filament para el catálogo de tipos de contratista.
 *
 * Gestiona el modelo App\Models\TipoContratista en el panel de
 * administración, sin grupo de navegación específico.
 */
class TipoContratistaResource extends Resource
{
    protected static ?string $model = TipoContratista::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Denominacion';

    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('Tipo_contratista')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('Denominacion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('Ultimo_codigo')
                    ->numeric(),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Denominacion')
            ->defaultSort('Tipo_contratista')
            ->columns([
                Tables\Columns\TextColumn::make('Tipo_contratista')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Denominacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Ultimo_codigo')
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
            'index' => Pages\ListTipoContratistas::route('/'),
            'create' => Pages\CreateTipoContratista::route('/create'),
            'edit' => Pages\EditTipoContratista::route('/{record}/edit'),
        ];
    }
}
