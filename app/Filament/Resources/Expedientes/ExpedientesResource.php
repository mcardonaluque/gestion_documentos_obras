<?php

namespace App\Filament\Resources\Expedientes;

use App\Filament\Traits\CommonFilters;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Expedientes\Pages\ListExpedientes;
use App\Filament\Resources\Expedientes\Pages\CreateExpedientes;
use App\Filament\Resources\Expedientes\Pages\EditExpedientes;
use App\Filament\Resources\ExpedientesResource\Pages;
use App\Models\Expediente;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

/**
 * Recurso Filament para la administración de expedientes de obras.
 *
 * Gestiona el modelo App\Models\Expediente en el panel de administración,
 * grupo «Documentación», con pertenencia al tenant mediante la relación
 * «team». Usa el trait CommonFilters para los filtros compartidos de la tabla.
 */
class ExpedientesResource extends Resource
{
    use CommonFilters;

    protected static ?string $model = Expediente::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup="Documentación";
    protected static ?string $tenantOwnershipRelationshipName = 'team';


    /** Define el esquema del formulario del recurso. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(5)
            ->components([
                TextInput::make('expediente_id')
                    ->required()
                    ->maxLength(510),
                TextInput::make('Codigo_plan')
                    ->required()
                    ->maxLength(510),
                TextInput::make('ao_ejecucion')
                    ->label('Año de ejecución')
                    ->required()
                    ->numeric(),
                TextInput::make('referencia')
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                TextInput::make('nombre_obra')
                    ->required()
                    ->maxLength(510),
                TextInput::make('cod_estado')
                    ->required()
                    ->maxLength(6),
                TextInput::make('cod_fase')
                    ->required()
                    ->maxLength(510),
                TextInput::make('cod_estado_help')
                    ->required()
                    ->maxLength(510),
            ]);
    }

    /** Configura la tabla de listado del recurso. */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('Codigo_plan')
                    ->searchable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombre_obra')
                    ->searchable(),
                TextColumn::make('cod_estado')
                    ->searchable(),
                TextColumn::make('cod_fase')
                    ->searchable(),
                TextColumn::make('cod_estado_help')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                ...self::getCommonFilters(),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->deferFilters(false)
            ->defaultSort('ao_ejecucion', 'desc')
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
            //
        ];
    }

    /** Declara las páginas y rutas del recurso. */
    public static function getPages(): array
    {
        return [
            'index' => ListExpedientes::route('/'),
            'create' => CreateExpedientes::route('/create'),
            'edit' => EditExpedientes::route('/{record}/edit'),
        ];
    }
}
