<?php

namespace App\Filament\Resources\TiposAvisos;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TiposAvisos\Pages\ListTiposAvisos;
use App\Filament\Resources\TiposAvisos\Pages\CreateTiposAviso;
use App\Filament\Resources\TiposAvisos\Pages\EditTiposAviso;
use App\Filament\Resources\TiposAvisoResource\Pages;
use App\Filament\Resources\TiposAvisoResource\RelationManagers;
use App\Models\TiposAviso;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TiposAvisoResource extends Resource
{
    protected static ?string $model = TiposAviso::class;
    protected static bool $isScopedToTenant = false;
    protected static string | \UnitEnum | null $navigationGroup="Notificaciones";
    protected static ?string $navigationLabel = 'Tipos de Avisos';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //

                TextInput::make('TipoAviso')
                    ->label('Tipo de Aviso')
                    ->required(),
                TextInput::make('Des')
                    ->label('Nombre del tipo')
                    ->maxLength(100)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('TipoAviso')
            ->columns([
                //

                TextColumn::make('TipoAviso')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Des')
                    ->searchable(),
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
            'index' => ListTiposAvisos::route('/'),
            'create' => CreateTiposAviso::route('/create'),
            'edit' => EditTiposAviso::route('/{record}/edit'),
        ];
    }
}
