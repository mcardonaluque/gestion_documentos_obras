<?php

namespace App\Filament\Resources\FaseDocumentos;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FaseDocumentos\Pages\ListFaseDocumentos;
use App\Filament\Resources\FaseDocumentos\Pages\CreateFaseDocumento;
use App\Filament\Resources\FaseDocumentos\Pages\EditFaseDocumento;
use App\Filament\Resources\FaseDocumentoResource\Pages;
use App\Filament\Resources\FaseDocumentoResource\RelationManagers;
use App\Models\FaseDocumento;
use Filament\Forms;
use Filament\Schemas\Schema;
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
    protected static string | \UnitEnum | null $navigationGroup = 'Documentación';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
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
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cod_fase')
                    ->label('Código')
                    ->required()
                    ->maxLength(3),
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(80),
                TextInput::make('descripcion')
                    ->required()
                    ->maxLength(510),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_fase')
                    ->searchable(),
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('descripcion')
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
            'index' => ListFaseDocumentos::route('/'),
            'create' => CreateFaseDocumento::route('/create'),
            'edit' => EditFaseDocumento::route('/{record}/edit'),
        ];
    }
}
