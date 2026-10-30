<?php

namespace App\Filament\Resources\DestinoDeDocumentos;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DestinoDeDocumentos\Pages\ListDestinoDeDocumentos;
use App\Filament\Resources\DestinoDeDocumentos\Pages\CreateDestinoDeDocumentos;
use App\Filament\Resources\DestinoDeDocumentos\Pages\EditDestinoDeDocumentos;
use App\Filament\Resources\DestinoDeDocumentosResource\Pages;
use App\Filament\Resources\DestinoDeDocumentosResource\RelationManagers;
use App\Models\DestinoDeDocumentos;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Recurso Filament para el catálogo de destinos/procedencias de documentos.
 *
 * Gestiona el modelo App\Models\DestinoDeDocumentos en el panel de
 * administración, grupo de navegación «Documentación».
 */
class DestinoDeDocumentosResource extends Resource
{
    protected static ?string $model = DestinoDeDocumentos::class;
    protected static string | \UnitEnum | null $navigationGroup = 'Documentación';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('destino')
                    ->maxLength(60),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('destino')
                    ->sortable()
                    ->searchable(),
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
            'index' => ListDestinoDeDocumentos::route('/'),
            'create' => CreateDestinoDeDocumentos::route('/create'),
            'edit' => EditDestinoDeDocumentos::route('/{record}/edit'),
        ];
    }
}
