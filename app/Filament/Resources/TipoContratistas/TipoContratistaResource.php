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

class TipoContratistaResource extends Resource
{
    protected static ?string $model = TipoContratista::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Denominacion';

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

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Denominacion')
            ->columns([
                Tables\Columns\TextColumn::make('Tipo_contratista')
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipoContratistas::route('/'),
            'create' => Pages\CreateTipoContratista::route('/create'),
            'edit' => Pages\EditTipoContratista::route('/{record}/edit'),
        ];
    }
}
