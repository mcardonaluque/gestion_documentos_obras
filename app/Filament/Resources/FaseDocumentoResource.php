<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaseDocumentoResource\Pages;
use App\Filament\Resources\FaseDocumentoResource\RelationManagers;
use App\Models\FaseDocumento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaseDocumentoResource extends Resource
{
    protected static ?string $model = FaseDocumento::class;
    
    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationLabel = 'Fases de documentos';
    protected static ?string $navigationGroup = 'Documentación';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_fase')
                    ->label('Código')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(80),
                Forms\Components\TextInput::make('descripcion')
                    ->maxLength(510),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_fase')
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
            'index' => Pages\ListFaseDocumentos::route('/'),
            'create' => Pages\CreateFaseDocumento::route('/create'),
            'edit' => Pages\EditFaseDocumento::route('/{record}/edit'),
        ];
    }
}
