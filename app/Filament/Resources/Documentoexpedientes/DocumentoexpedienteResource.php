<?php

namespace App\Filament\Resources\Documentoexpedientes;

use Filament\Forms\Components\Select;
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

/**
 * Recurso Filament para la gestión directa de documentos de expediente.
 *
 * Gestiona el modelo App\Models\Documentoexpediente en el panel de
 * administración, grupo «Documentación». Ofrece el CRUD completo del
 * documento (plan, referencia, fechas, CSV, estado...) y precarga la
 * relación de estados en el listado.
 */
class DocumentoexpedienteResource extends Resource
{
    protected static ?string $model = Documentoexpediente::class;
    protected static string | \UnitEnum | null $navigationGroup="Documentación";
    protected static ?string $navigationLabel = 'Documentos de Expedientes';
    protected static ?string $modelLabel = 'Documento de Expediente';
    protected static ?string $pluralModelLabel = 'Documentos de Expedientes';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    /** Define el esquema del formulario del recurso. */
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
                Select::make('estado')
                    ->relationship('estados', 'nombre')
                    ->required(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
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
                TextColumn::make('cod_documento')
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
                TextColumn::make('estados.nombre')
                    ->label('Estado')
                    ->formatStateUsing(fn (?string $state): string => trim((string) $state))
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

    /** Precarga la relación de estados para optimizar el listado. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('estados');
    }

    /** Declara los relation managers asociados al recurso. */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /** Declara las páginas y rutas del recurso. */
    public static function getPages(): array
    {
        return [
            'index' => ListDocumentoexpedientes::route('/'),
            'create' => CreateDocumentoexpediente::route('/create'),
            'edit' => EditDocumentoexpediente::route('/{record}/edit'),
        ];
    }
}
