<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentoExpedienteResource\Pages;
use App\Filament\Resources\DocumentoExpedienteResource\RelationManagers;
use App\Models\DocumentoExpediente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoExpedienteResource extends Resource
{
    protected static ?string $model = DocumentoExpediente::class;
    protected static ?string $navigationLabel = 'Documentos de Expedientes';
    protected static ?string $navigationGroup = 'Documentación';
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
            'index' => Pages\ListDocumentoExpedientes::route('/'),
            'create' => Pages\CreateDocumentoExpediente::route('/create'),
            'edit' => Pages\EditDocumentoExpediente::route('/{record}/edit'),
        ];
    }
}
