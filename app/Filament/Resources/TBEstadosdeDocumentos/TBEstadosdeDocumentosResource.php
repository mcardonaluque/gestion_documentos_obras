<?php

namespace App\Filament\Resources\TBEstadosdeDocumentos;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TBEstadosdeDocumentos\Pages\ListTBEstadosdeDocumentos;
use App\Filament\Resources\TBEstadosdeDocumentos\Pages\CreateTBEstadosdeDocumentos;
use App\Filament\Resources\TBEstadosdeDocumentos\Pages\EditTBEstadosdeDocumentos;
use App\Filament\Resources\TBEstadosdeDocumentosResource\Pages;
use App\Filament\Resources\TBEstadosdeDocumentosResource\RelationManagers;
use App\Models\TBEstadosdeDocumentos;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TBEstadosdeDocumentosResource extends Resource
{
    protected static ?string $model = TBEstadosdeDocumentos::class;

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
            'index' => ListTBEstadosdeDocumentos::route('/'),
            'create' => CreateTBEstadosdeDocumentos::route('/create'),
            'edit' => EditTBEstadosdeDocumentos::route('/{record}/edit'),
        ];
    }
}
