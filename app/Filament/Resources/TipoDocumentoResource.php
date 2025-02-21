<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoDocumentoResource\Pages;
use App\Filament\Resources\TipoDocumentoResource\RelationManagers;
use App\Models\TipoDocumento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoDocumentoResource extends Resource
{
    protected static ?string $model = TipoDocumento::class;
    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Documentación';
    protected static ?string $navigationLabel = 'Tipos de documentos';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                ->hidden(),
                Forms\Components\TextInput::make('IdTipo')
                    ->label('Tipo de documento')
                    ->required(),
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del tipo')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción del tipo')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Id')
                ->hidden(),
                Tables\Columns\TextColumn::make('IdTipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
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
            'index' => Pages\ListTipoDocumentos::route('/'),
            'create' => Pages\CreateTipoDocumento::route('/create'),
            'edit' => Pages\EditTipoDocumento::route('/{record}/edit'),
        ];
    }
}
