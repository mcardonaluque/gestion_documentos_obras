<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiposAvisoResource\Pages;
use App\Filament\Resources\TiposAvisoResource\RelationManagers;
use App\Models\TiposAviso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TiposAvisoResource extends Resource
{
    protected static ?string $model = TiposAviso::class;
    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationGroup="Notificaciones";
    protected static ?string $navigationLabel = 'Tipos de Avisos';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
               
                Forms\Components\TextInput::make('TipoAviso')
                    ->label('Tipo de Aviso')
                    ->required(),
                Forms\Components\TextInput::make('Des')
                    ->label('Nombre del tipo')
                    ->maxLength(100)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
              
                Tables\Columns\TextColumn::make('TipoAviso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Des')
                    ->searchable(),
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
            'index' => Pages\ListTiposAvisos::route('/'),
            'create' => Pages\CreateTiposAviso::route('/create'),
            'edit' => Pages\EditTiposAviso::route('/{record}/edit'),
        ];
    }
}
