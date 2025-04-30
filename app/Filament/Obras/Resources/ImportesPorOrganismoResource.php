<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\ImportesPorOrganismoResource\Pages;
use App\Filament\Obras\Resources\ImportesPorOrganismoResource\RelationManagers;
use App\Models\ImportesPorOrganismo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImportesPorOrganismoResource extends Resource
{
    protected static ?string $model = ImportesPorOrganismo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = true;

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
              //      Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListImportesPorOrganismos::route('/'),
            'create' => Pages\CreateImportesPorOrganismo::route('/create'),
            'edit' => Pages\EditImportesPorOrganismo::route('/{record}/edit'),
        ];
    }
}
