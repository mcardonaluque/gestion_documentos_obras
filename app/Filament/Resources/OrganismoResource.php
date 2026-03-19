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

class OrganismoResource extends Resource
{
    protected static ?string $model = Organismo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'organismo';

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

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('organismo')
            ->columns([
                Tables\Columns\TextColumn::make('codigo_organismo')
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganismos::route('/'),
            'create' => Pages\CreateOrganismo::route('/create'),
            'edit' => Pages\EditOrganismo::route('/{record}/edit'),
        ];
    }
}
