<?php

declare(strict_types=1);

namespace App\Filament\Resources\ExpedienteFechaHitos;

use App\Filament\Resources\ExpedienteFechaHitos\Pages\ListExpedienteFechaHitos;
use App\Models\ExpedienteFechaHito;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExpedienteFechaHitoResource extends Resource
{
    protected static ?string $model = ExpedienteFechaHito::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Validacion de fechas';

    protected static ?string $navigationLabel = 'Hitos materializados';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('expediente_id')->label('Expediente')->searchable()->sortable(),
                TextColumn::make('codigo_hito')->searchable()->sortable(),
                TextColumn::make('fecha')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('tabla_origen')->label('Tabla')->searchable(),
                TextColumn::make('campo_origen')->label('Campo')->searchable(),
                TextColumn::make('team_id')->label('Team')->sortable(),
                TextColumn::make('updated_at')->label('Actualizado')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('tabla_origen')
                    ->options(fn (): array => ExpedienteFechaHito::query()
                        ->orderBy('tabla_origen')
                        ->distinct()
                        ->pluck('tabla_origen', 'tabla_origen')
                        ->toArray()),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpedienteFechaHitos::route('/'),
        ];
    }
}
