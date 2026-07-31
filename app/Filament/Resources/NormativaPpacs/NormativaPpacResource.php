<?php

declare(strict_types=1);

namespace App\Filament\Resources\NormativaPpacs;

use App\Filament\Resources\NormativaPpacs\Pages;
use App\Models\NormativaPpac;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NormativaPpacResource extends Resource
{
    protected static ?string $model = NormativaPpac::class;

    protected static bool $isScopedToTenant = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = 'Administracion de prorrogas';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Normativa PPAC';

    protected static ?string $modelLabel = 'Normativa PPAC';

    protected static ?string $pluralModelLabel = 'Normativas PPAC';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('ao_plan')
                    ->label('Año plan')
                    ->required()
                    ->numeric()
                    ->minValue(2000),
                TextInput::make('ao_fin_plan')
                    ->label('Año fin plan')
                    ->required()
                    ->numeric()
                    ->minValue(2000),
                DateTimePicker::make('fecha_aprobacion_plan')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_publicacion_definitiva')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_terminacion_plan')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_justificacion_plan')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_presentacion_proyecto')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_presentacion_documentacion')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_cesion_obra')
                    ->label('Fecha limite de cesión de la obra')
                    ->nullable()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_proyecto_diputacion')
                    ->label('Fecha limite proyecto diputación')
                    ->nullable()
                    ->default(fn () => now()->startOfDay()),
                TextInput::make('dias_prorroga_max_porcentaje')
                    ->label('% maximo de ampliacion')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100),
                Textarea::make('observaciones')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('ao_plan', 'desc')
            ->columns([
                TextColumn::make('ao_plan')
                    ->label('Año plan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ao_fin_plan')
                    ->label('Año fin')
                    ->sortable(),
                TextColumn::make('fecha_aprobacion_plan')
                    ->label('Aprobación')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_publicacion_definitiva')
                    ->label('Publicacion')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('fecha_limite_terminacion_plan')
                    ->label('Limite terminacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_limite_justificacion_plan')
                    ->label('Limite justificacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_limite_presentacion_proyecto')
                    ->label('Limite proyecto')
                    ->dateTime(),
                TextColumn::make('fecha_limite_presentacion_documentacion')
                    ->label('Limite documentacion')
                    ->dateTime(),
                TextColumn::make('fecha_limite_cesion_obra')
                    ->label('Límite cesión obra')
                    ->dateTime(),
                TextColumn::make('fecha_limite_proyecto_diputacion')
                    ->label('Límite proyecto diputación')
                    ->dateTime(),
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
            'index' => Pages\ListNormativaPpacs::route('/'),
            'create' => Pages\CreateNormativaPpac::route('/create'),
            'edit' => Pages\EditNormativaPpac::route('/{record}/edit'),
        ];
    }
}
