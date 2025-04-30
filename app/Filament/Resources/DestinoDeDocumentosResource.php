<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinoDeDocumentosResource\Pages;
use App\Filament\Resources\DestinoDeDocumentosResource\RelationManagers;
use App\Models\DestinoDeDocumentos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DestinoDeDocumentosResource extends Resource
{
    protected static ?string $model = DestinoDeDocumentos::class;
    protected static ?string $navigationGroup = 'Documentación';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('destino')
                    ->maxLength(60),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destino')
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
            'index' => Pages\ListDestinoDeDocumentos::route('/'),
            'create' => Pages\CreateDestinoDeDocumentos::route('/create'),
            'edit' => Pages\EditDestinoDeDocumentos::route('/{record}/edit'),
        ];
    }
}
