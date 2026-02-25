<?php

namespace App\Filament\Obras\Resources\ObrasCedidas;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\ObrasCedidas\Pages\ListObrasCedidas;
use App\Filament\Obras\Resources\ObrasCedidas\Pages\CreateObrasCedidas;
use App\Filament\Obras\Resources\ObrasCedidas\Pages\EditObrasCedidas;
use App\Filament\Obras\Resources\ObrasCedidasResource\Pages;
use App\Filament\Obras\Resources\ObrasCedidasResource\RelationManagers;
use App\Models\ObraCedida;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObrasCedidasResource extends Resource
{
    protected static ?string $model = ObraCedida::class;

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
            'index' => ListObrasCedidas::route('/'),
            'create' => CreateObrasCedidas::route('/create'),
            'edit' => EditObrasCedidas::route('/{record}/edit'),
        ];
    }
}
