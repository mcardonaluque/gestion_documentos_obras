<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\ObrasCedidasResource\Pages;
use App\Filament\Obras\Resources\ObrasCedidasResource\RelationManagers;
use App\Models\ObraCedida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObrasCedidasResource extends Resource
{
    protected static ?string $model = ObraCedida::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListObrasCedidas::route('/'),
            'create' => Pages\CreateObrasCedidas::route('/create'),
            'edit' => Pages\EditObrasCedidas::route('/{record}/edit'),
        ];
    }
}
