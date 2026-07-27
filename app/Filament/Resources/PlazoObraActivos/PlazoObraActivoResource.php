<?php

declare(strict_types=1);

namespace App\Filament\Resources\PlazoObraActivos;

use App\Filament\Resources\PlazoObraActivos\Pages;
use App\Models\PlazoObraActivo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PlazoObraActivoResource extends Resource
{
    protected static ?string $model = PlazoObraActivo::class;

    protected static bool $isScopedToTenant = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string | \UnitEnum | null $navigationGroup = 'Administracion de prorrogas';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Plazos de obra activos';

    protected static ?string $modelLabel = 'Plazo activo';

    protected static ?string $pluralModelLabel = 'Plazos activos';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->required()
                    ->maxLength(255),
                Select::make('normativa_ppac_id')
                    ->label('Normativa PPAC')
                    ->relationship('normativa', 'ao_plan')
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('fase')
                    ->required()
                    ->options([
                        'proyecto_memoria' => 'Proyecto/memoria',
                        'documentacion' => 'Documentacion',
                        'ejecucion' => 'Ejecucion',
                        'justificacion' => 'Justificacion',
                    ]),
                Select::make('fuente_ultima_actualizacion')
                    ->label('Fuente actualizacion')
                    ->required()
                    ->options([
                        'normativa' => 'Normativa',
                        'prorroga' => 'Prorroga',
                        'manual' => 'Manual',
                    ])
                    ->default('manual'),
                DateTimePicker::make('fecha_inicio')
                    ->required(),
                DateTimePicker::make('fecha_fin')
                    ->required(),
                TextInput::make('dias_base')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('dias_prorroga_acumulados')
                    ->label('Dias de prorroga acumulados')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('activo')
                    ->required()
                    ->default(true),
                TextInput::make('team_id')
                    ->numeric()
                    ->minValue(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('normativa.ao_plan')
                    ->label('Normativa')
                    ->sortable(),
                TextColumn::make('fase')
                    ->badge()
                    ->searchable(),
                TextColumn::make('fecha_inicio')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_fin')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('dias_base')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dias_prorroga_acumulados')
                    ->label('Dias prorroga')
                    ->numeric(),
                TextColumn::make('fuente_ultima_actualizacion')
                    ->label('Fuente')
                    ->badge(),
                IconColumn::make('activo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('fase')
                    ->options([
                        'proyecto_memoria' => 'Proyecto/memoria',
                        'documentacion' => 'Documentacion',
                        'ejecucion' => 'Ejecucion',
                        'justificacion' => 'Justificacion',
                    ]),
                SelectFilter::make('activo')
                    ->options([
                        '1' => 'Activos',
                        '0' => 'Inactivos',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlazoObraActivos::route('/'),
            'create' => Pages\CreatePlazoObraActivo::route('/create'),
            'edit' => Pages\EditPlazoObraActivo::route('/{record}/edit'),
        ];
    }
}
