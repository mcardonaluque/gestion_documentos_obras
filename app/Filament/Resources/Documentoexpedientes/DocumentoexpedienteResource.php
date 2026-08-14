<?php

namespace App\Filament\Resources\Documentoexpedientes;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Documentoexpedientes\Pages\ListDocumentoexpedientes;
use App\Filament\Resources\Documentoexpedientes\Pages\CreateDocumentoexpediente;
use App\Filament\Resources\Documentoexpedientes\Pages\EditDocumentoexpediente;
use App\Filament\Resources\DocumentoexpedienteResource\Pages;
use App\Filament\Resources\DocumentoexpedienteResource\RelationManagers;
use App\Models\Documentoexpediente;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoexpedienteResource extends Resource
{
    protected static ?string $model = Documentoexpediente::class;
    protected static string | \UnitEnum | null $navigationGroup="Documentación";
    protected static ?string $navigationLabel = 'Documentos de Expedientes';
    protected static ?string $modelLabel = 'Documento de Expediente';
    protected static ?string $pluralModelLabel = 'Documentos de Expedientes';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(45),
                TextInput::make('referencia')
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->numeric()
                    ->default(null),
                TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                DatePicker::make('fechaincorporacion')
                    ->required(),
                DatePicker::make('fechaHelp'),
                TextInput::make('cod_dcoumento')
                    ->required()
                    ->numeric(),
                TextInput::make('expediente_id')
                    ->required()
                    ->maxLength(45),
                TextInput::make('csv')
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                TextInput::make('nsecuencia')
                    ->numeric()
                    ->default(null),
                TextInput::make('estado.nombre')
                    ->required()
                    ->numeric(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('Codigo_Plan')
            ->columns([
                TextColumn::make('Codigo_Plan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('fechaHelp')
                    ->date()
                    ->sortable(),
                TextColumn::make('cod_dcoumento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('csv')
                    ->searchable(),
                TextColumn::make('nregistro')
                    ->searchable(),
                TextColumn::make('nsecuencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado.nombre')
                    ->numeric()
                    ->sortable(),
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
            'index' => ListDocumentoexpedientes::route('/'),
            'create' => CreateDocumentoexpediente::route('/create'),
            'edit' => EditDocumentoexpediente::route('/{record}/edit'),
        ];
    }
}
