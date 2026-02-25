<?php

namespace App\Filament\Resources\TablaDeCarreteras;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TablaDeCarreteras\Pages\ListTablaDeCarreteras;
use App\Filament\Resources\TablaDeCarreteras\Pages\CreateTablaDeCarretera;
use App\Filament\Resources\TablaDeCarreteras\Pages\EditTablaDeCarretera;
use App\Filament\Resources\TablaDeCarreteraResource\Pages;
use App\Filament\Resources\TablaDeCarreteraResource\RelationManagers;
use App\Models\TablaDeCarretera;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TablaDeCarreteraResource extends Resource
{
    protected static ?string $model = TablaDeCarretera::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => ListTablaDeCarreteras::route('/'),
            'create' => CreateTablaDeCarretera::route('/create'),
            'edit' => EditTablaDeCarretera::route('/{record}/edit'),
        ];
    }
}
