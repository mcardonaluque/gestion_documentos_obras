<?php

namespace App\Filament\Resources\TBEstadosdeDocumentos;

use Filament\Forms\Components\TextInput;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\TBEstadosdeDocumentos\Pages\ListTBEstadosdeDocumentos;
use App\Filament\Resources\TBEstadosdeDocumentos\Pages\CreateTBEstadosdeDocumentos;
use App\Filament\Resources\TBEstadosdeDocumentos\Pages\EditTBEstadosdeDocumentos;
use App\Models\TBestadosdeDocumentos;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Recurso Filament para el catálogo de estados de documentos.
 *
 * Gestiona el modelo App\Models\TBestadosdeDocumentos en el panel de
 * administración con la etiqueta de navegación «Estados de Documentos».
 */
class TBEstadosdeDocumentosResource extends Resource
{
    protected static ?string $model = TBestadosdeDocumentos::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
     protected static ?string $navigationLabel = 'Estados de Documentos';

    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('descripcion')
                    ->label('Descripcion')
                    ->maxLength(255),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->label('Descripcion')
                    ->searchable()
                    ->wrap(),
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
            'index' => ListTBEstadosdeDocumentos::route('/'),
            'create' => CreateTBEstadosdeDocumentos::route('/create'),
            'edit' => EditTBEstadosdeDocumentos::route('/{record}/edit'),
        ];
    }
}
