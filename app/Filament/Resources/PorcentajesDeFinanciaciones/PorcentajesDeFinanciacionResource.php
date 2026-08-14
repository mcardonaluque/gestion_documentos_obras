<?php

namespace App\Filament\Resources\PorcentajesDeFinanciaciones;

use App\Filament\Resources\PorcentajesDeFinanciaciones\Pages\CreatePorcentajesDeFinanciacion;
use App\Filament\Resources\PorcentajesDeFinanciaciones\Pages\EditPorcentajesDeFinanciacion;
use App\Filament\Resources\PorcentajesDeFinanciaciones\Pages\ListPorcentajesDeFinanciaciones;
use App\Models\PorcentajesDeFinanciacion;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PorcentajesDeFinanciacionResource extends Resource
{
    protected static ?string $model = PorcentajesDeFinanciacion::class;
    protected static bool $isScopedToTenant = false;

    protected static ?string $modelLabel = 'Porcentaje de financiacion';
    protected static ?string $pluralModelLabel = 'Porcentajes de financiacion';
    protected static ?string $navigationLabel = 'Porcentajes financiacion';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-table-cells';
    protected static string | \UnitEnum | null $navigationGroup = 'Parametros';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Codigo_Plan')->label('Codigo plan')->maxLength(14)->required(),
                TextInput::make('Ao_ejecucion')->label('Ano ejecucion')->numeric()->required(),
                TextInput::make('Organismo')->maxLength(10)->required(),
                TextInput::make('ao_financiacion')->label('Ano financiacion')->numeric()->required(),
                TextInput::make('formula1')->label('Formula 1')->maxLength(10),
                TextInput::make('Porcentaje1')->label('Porcentaje 1')->numeric(),
                TextInput::make('formula2')->label('Formula 2')->maxLength(10),
                TextInput::make('Porcentaje2')->label('Porcentaje 2')->numeric(),
                TextInput::make('formula3')->label('Formula 3')->maxLength(10),
                TextInput::make('Porcentaje3')->label('Porcentaje 3')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('Codigo_Plan')
            ->columns([
                TextColumn::make('Codigo_Plan')->label('Codigo plan')->sortable()->searchable(),
                TextColumn::make('Ao_ejecucion')->label('Ao ejecucion')->sortable(),
                TextColumn::make('Organismo')->sortable()->searchable(),
                TextColumn::make('ao_financiacion')->label('Ano financiacion')->sortable(),
                TextColumn::make('formula1')->label('Formula 1')->sortable(),
                TextColumn::make('Porcentaje1')->label('Porcentaje 1')->numeric(2)->sortable(),
                TextColumn::make('formula2')->label('Formula 2')->sortable(),
                TextColumn::make('Porcentaje2')->label('Porcentaje 2')->numeric(2)->sortable(),
                TextColumn::make('formula3')->label('Formula 3')->sortable(),
                TextColumn::make('Porcentaje3')->label('Porcentaje 3')->numeric(2)->sortable(),
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
            'index' => ListPorcentajesDeFinanciaciones::route('/'),
            'create' => CreatePorcentajesDeFinanciacion::route('/create'),
            'edit' => EditPorcentajesDeFinanciacion::route('/{record}/edit'),
        ];
    }
}
