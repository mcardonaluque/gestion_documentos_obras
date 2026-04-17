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

class TBEstadosdeDocumentosResource extends Resource
{
    protected static ?string $model = TBestadosdeDocumentos::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
     protected static ?string $navigationLabel = 'Estados de Documentos';

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

    public static function table(Table $table): Table
    {
        return $table
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTBEstadosdeDocumentos::route('/'),
            'create' => CreateTBEstadosdeDocumentos::route('/create'),
            'edit' => EditTBEstadosdeDocumentos::route('/{record}/edit'),
        ];
    }
}
