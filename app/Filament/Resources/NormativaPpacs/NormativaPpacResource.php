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
                    ->label('Ano plan')
                    ->required()
                    ->numeric()
                    ->minValue(2000),
                TextInput::make('ao_fin_plan')
                    ->label('Ano fin plan')
                    ->required()
                    ->numeric()
                    ->minValue(2000),
                DateTimePicker::make('fecha_publicacion_definitiva')
                    ->required(),
                DateTimePicker::make('fecha_limite_terminacion_plan')
                    ->required(),
                DateTimePicker::make('fecha_limite_justificacion_plan')
                    ->required(),
                DateTimePicker::make('fecha_limite_presentacion_proyecto')
                    ->required(),
                DateTimePicker::make('fecha_limite_presentacion_documentacion')
                    ->required(),
                DateTimePicker::make('fecha_cesion_proyecto')
                    ->label('Fecha cesion proyecto')
                    ->nullable(),
                DateTimePicker::make('fecha_cesion_documentacion')
                    ->label('Fecha cesion documentacion')
                    ->nullable(),
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
                    ->label('Ano plan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ao_fin_plan')
                    ->label('Ano fin')
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
                TextColumn::make('fecha_cesion_proyecto')
                    ->label('Cesion proyecto')
                    ->dateTime(),
                TextColumn::make('fecha_cesion_documentacion')
                    ->label('Cesion documentacion')
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
