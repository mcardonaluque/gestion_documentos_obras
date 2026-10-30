<?php

namespace App\Filament\Resources\TipoDocumentos;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TipoDocumentos\Pages\ListTipoDocumentos;
use App\Filament\Resources\TipoDocumentos\Pages\CreateTipoDocumento;
use App\Filament\Resources\TipoDocumentos\Pages\EditTipoDocumento;
use App\Filament\Resources\TipoDocumentoResource\Pages;
use App\Filament\Resources\TipoDocumentoResource\RelationManagers;
use App\Models\TipoDocumento;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Recurso Filament para el catálogo de tipos de documentos.
 *
 * Gestiona el modelo App\Models\TipoDocumento en el panel de
 * administración, grupo «Documentación», sin aislamiento por tenant.
 */
class TipoDocumentoResource extends Resource
{
    protected static ?string $model = TipoDocumento::class;
    protected static bool $isScopedToTenant = false;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Documentación';
    protected static ?string $navigationLabel = 'Tipos de documentos';
    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                ->hidden(),
                TextInput::make('IdTipo')
                    ->label('Tipo de documento')
                    ->required(),
                TextInput::make('nombre')
                    ->label('Nombre del tipo')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('descripcion')
                    ->label('Descripción del tipo')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('IdTipo')
            ->columns([
                TextColumn::make('Id')
                ->hidden(),
                TextColumn::make('IdTipo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('descripcion')
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
            'index' => ListTipoDocumentos::route('/'),
            'create' => CreateTipoDocumento::route('/create'),
            'edit' => EditTipoDocumento::route('/{record}/edit'),
        ];
    }
}
