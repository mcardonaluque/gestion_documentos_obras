<?php

namespace App\Filament\Resources\FaseDocumentos;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FaseDocumentos\Pages\ListFaseDocumentos;
use App\Filament\Resources\FaseDocumentos\Pages\CreateFaseDocumento;
use App\Filament\Resources\FaseDocumentos\Pages\EditFaseDocumento;
use App\Filament\Resources\FaseDocumentoResource\Pages;
use App\Filament\Resources\FaseDocumentoResource\RelationManagers;
use App\Models\FaseDocumento;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Recurso Filament para el catálogo de fases de documentos.
 *
 * Gestiona el modelo App\Models\FaseDocumento en el panel de administración,
 * grupo «Documentación», sin aislamiento por tenant. Integra Filament Shield
 * mediante getPermissionPrefixes().
 */
class FaseDocumentoResource extends Resource
{
    protected static ?string $model = FaseDocumento::class;

    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationLabel = 'Fases de documentos';
    protected static string | \UnitEnum | null $navigationGroup = 'Documentación';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    /** Prefijos de permisos gestionados por Filament Shield para este recurso. */
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cod_fase')
                    ->label('Código')
                    ->required()
                    ->maxLength(3),
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(80),
                TextInput::make('descripcion')
                    ->required()
                    ->maxLength(510),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('cod_fase')
            ->columns([
                TextColumn::make('cod_fase')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->searchable()
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
            'index' => ListFaseDocumentos::route('/'),
            'create' => CreateFaseDocumento::route('/create'),
            'edit' => EditFaseDocumento::route('/{record}/edit'),
        ];
    }
}
