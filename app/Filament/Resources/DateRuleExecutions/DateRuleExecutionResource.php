<?php

declare(strict_types=1);

namespace App\Filament\Resources\DateRuleExecutions;

use App\Filament\Resources\DateRuleExecutions\Pages\ListDateRuleExecutions;
use App\Models\DateRuleExecution;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DateRuleExecutionResource extends Resource
{
    protected static ?string $model = DateRuleExecution::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Validacion de fechas';

    protected static ?string $navigationLabel = 'Ejecuciones de reglas';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('evaluated_at', 'desc')
            ->columns([
                TextColumn::make('evaluated_at')->label('Evaluada')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('rule.nombre')->label('Regla')->searchable()->sortable(),
                TextColumn::make('expediente_id')->label('Expediente')->searchable(),
                TextColumn::make('source_table')->label('Tabla')->searchable(),
                TextColumn::make('source_field')->label('Campo')->searchable(),
                IconColumn::make('passed')->label('Cumple')->boolean(),
                IconColumn::make('triggered')->label('Dispara')->boolean(),
                TextColumn::make('severity')->badge(),
                TextColumn::make('action')->badge(),
                TextColumn::make('message')->wrap()->limit(80)->tooltip(fn (DateRuleExecution $record): string => $record->message),
            ])
            ->filters([
                SelectFilter::make('severity')
                    ->options([
                        'warning' => 'Advertencia',
                        'error' => 'Error',
                        'notification' => 'Notificacion',
                    ]),
                SelectFilter::make('passed')
                    ->options([
                        '1' => 'Cumplidas',
                        '0' => 'No cumplidas',
                    ]),
                SelectFilter::make('triggered')
                    ->options([
                        '1' => 'Disparadas',
                        '0' => 'No disparadas',
                    ]),
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
            'index' => ListDateRuleExecutions::route('/'),
        ];
    }
}
