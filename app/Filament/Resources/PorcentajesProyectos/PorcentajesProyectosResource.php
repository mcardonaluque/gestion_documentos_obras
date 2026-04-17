<?php

namespace App\Filament\Resources\PorcentajesProyectos;

use App\Filament\Resources\PorcentajesProyectos\Pages\CreatePorcentajesProyectos;
use App\Filament\Resources\PorcentajesProyectos\Pages\EditPorcentajesProyectos;
use App\Filament\Resources\PorcentajesProyectos\Pages\ListPorcentajesProyectos;
use App\Models\PorcentajesProyectos;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Resource Filament para el mantenimiento de porcentajes base aplicables a proyectos.
 *
 * Permite a perfiles de administración ajustar la parametrización económica
 * usada después por los cálculos automáticos de presupuesto y honorarios.
 */
class PorcentajesProyectosResource extends Resource
{
    protected static ?string $model = PorcentajesProyectos::class;
    protected static bool $isScopedToTenant = false;

    protected static ?string $modelLabel = 'Porcentaje de proyecto';
    protected static ?string $pluralModelLabel = 'Porcentajes de proyectos';
    protected static ?string $navigationLabel = 'Porcentajes proyectos';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calculator';
    protected static string | \UnitEnum | null $navigationGroup = 'Parametros';

    /** Define el formulario de edición de porcentajes anuales del proyecto. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('AoProyecto')->label('Ano proyecto')->numeric()->required(),
                TextInput::make('GG')->label('GG (%)')->numeric()->required(),
                TextInput::make('BI')->label('BI (%)')->numeric()->required(),
                TextInput::make('CC')->label('CC (%)')->numeric()->required(),
                TextInput::make('IV')->label('IV (%)')->numeric()->required(),
                TextInput::make('SU')->label('SU (%)')->numeric()->required(),
                TextInput::make('HD')->label('HD (%)')->numeric()->required(),
                TextInput::make('HR')->label('HR (%)')->numeric()->required(),
                TextInput::make('BT')->label('BT (%)')->numeric()->required(),
            ]);
    }

    /** Configura la tabla de consulta y mantenimiento de la parametrización económica. */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('AoProyecto')->label('Ano proyecto')->sortable()->searchable(),
                TextColumn::make('GG')->label('GG (%)')->numeric(2)->sortable(),
                TextColumn::make('BI')->label('BI (%)')->numeric(2)->sortable(),
                TextColumn::make('CC')->label('CC (%)')->numeric(2)->sortable(),
                TextColumn::make('IV')->label('IV (%)')->numeric(2)->sortable(),
                TextColumn::make('SU')->label('SU (%)')->numeric(2)->sortable(),
                TextColumn::make('HD')->label('HD (%)')->numeric(2)->sortable(),
                TextColumn::make('HR')->label('HR (%)')->numeric(2)->sortable(),
                TextColumn::make('BT')->label('BT (%)')->numeric(2)->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPorcentajesProyectos::route('/'),
            'create' => CreatePorcentajesProyectos::route('/create'),
            'edit' => EditPorcentajesProyectos::route('/{record}/edit'),
        ];
    }
}
