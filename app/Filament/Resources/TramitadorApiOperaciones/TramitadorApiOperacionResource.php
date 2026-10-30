<?php

declare(strict_types=1);

namespace App\Filament\Resources\TramitadorApiOperaciones;

use App\Filament\Resources\TramitadorApiOperaciones\Pages\CreateTramitadorApiOperacion;
use App\Filament\Resources\TramitadorApiOperaciones\Pages\EditTramitadorApiOperacion;
use App\Filament\Resources\TramitadorApiOperaciones\Pages\ListTramitadorApiOperaciones;
use App\Filament\Resources\TramitadorApiOperaciones\RelationManagers\ParametrosRelationManager;
use App\Models\TramitadorApiOperacion;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Recurso Filament para las funciones (operaciones) del API del tramitador.
 *
 * Gestiona el modelo App\Models\TramitadorApiOperacion en el panel de
 * administración, grupo «Tramitador» y slug «tramitador-api-operaciones».
 * Define código, método HTTP y ruta de cada operación, y gestiona sus
 * parámetros de entrada/salida mediante ParametrosRelationManager.
 */
class TramitadorApiOperacionResource extends Resource
{
    protected static ?string $model = TramitadorApiOperacion::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string | \UnitEnum | null $navigationGroup = 'Tramitador';

    protected static ?string $navigationLabel = 'Funciones API';

    protected static ?string $modelLabel = 'función API';

    protected static ?string $pluralModelLabel = 'funciones API';

    protected static ?string $slug = 'tramitador-api-operaciones';

    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(160),
                Select::make('metodo_http')
                    ->label('Método HTTP')
                    ->options([
                        'GET' => 'GET',
                        'POST' => 'POST',
                        'PUT' => 'PUT',
                        'PATCH' => 'PATCH',
                        'DELETE' => 'DELETE',
                    ])
                    ->required()
                    ->default('POST'),
                TextInput::make('ruta')
                    ->label('Ruta')
                    ->helperText('Ejemplo: /{dir_emisor}/cat/exp')
                    ->required()
                    ->maxLength(500)
                    ->columnSpan(2),
                Select::make('tipo_contenido')
                    ->label('Tipo de contenido')
                    ->options([
                        'json' => 'JSON',
                        'form' => 'Formulario',
                        'multipart' => 'Multipart',
                    ])
                    ->required()
                    ->default('json'),
                TextInput::make('timeout_segundos')
                    ->label('Tiempo máximo (segundos)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(65535),
                Toggle::make('activa')
                    ->label('Activa')
                    ->default(true),
                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('codigo')
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('metodo_http')
                    ->label('Método')
                    ->badge()
                    ->sortable(),
                TextColumn::make('ruta')
                    ->label('Ruta')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('tipo_contenido')
                    ->label('Contenido')
                    ->badge(),
                TextColumn::make('parametros_count')
                    ->label('Parámetros')
                    ->counts('parametros'),
                IconColumn::make('activa')
                    ->label('Activa')
                    ->boolean(),
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

    /** Declara los relation managers asociados al recurso. */
    public static function getRelations(): array
    {
        return [
            ParametrosRelationManager::class,
        ];
    }

    /** Declara las páginas y rutas del recurso. */
    public static function getPages(): array
    {
        return [
            'index' => ListTramitadorApiOperaciones::route('/'),
            'create' => CreateTramitadorApiOperacion::route('/create'),
            'edit' => EditTramitadorApiOperacion::route('/{record}/edit'),
        ];
    }
}
