<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\NormativaPpacs;

use App\Filament\Obras\Resources\NormativaPpacs\Pages\CreateNormativaPpac;
use App\Filament\Obras\Resources\NormativaPpacs\Pages\EditNormativaPpac;
use App\Filament\Obras\Resources\NormativaPpacs\Pages\ListNormativaPpacs;
use App\Models\NormativaPpac;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NormativaPpacResource extends Resource
{
    protected static ?string $model = NormativaPpac::class;

    protected static ?string $navigationLabel = 'Normativa PPAC';

    protected static ?string $modelLabel = 'Normativa PPAC';

    protected static ?string $pluralModelLabel = 'Normativas PPAC';

    protected static ?int $navigationSort = 5;

    protected static string | \UnitEnum | null $navigationGroup = 'Aprobacion de Obras';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('ao_plan')->label('Año plan')->required()->numeric()->minValue(2000),
            TextInput::make('ao_fin_plan')->label('Año fin plan')->required()->numeric()->minValue(2000),
            DatePicker::make('fecha_publicacion_definitiva')->required(),
            DatePicker::make('fecha_limite_terminacion_plan')->required(),
            DatePicker::make('fecha_limite_justificacion_plan')->required(),
            DatePicker::make('fecha_limite_presentacion_proyecto')->required(),
            DatePicker::make('fecha_limite_presentacion_documentacion')->required(),
            TextInput::make('dias_prorroga_max_porcentaje')
                ->label('% máximo de ampliación')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(100),
            Textarea::make('observaciones')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('ao_plan', 'desc')
            ->columns([
                TextColumn::make('ao_plan')->label('Año plan')->sortable()->searchable(),
                TextColumn::make('ao_fin_plan')->label('Año fin')->sortable(),
                TextColumn::make('fecha_publicacion_definitiva')->label('Publicación')->date()->sortable(),
                TextColumn::make('fecha_limite_terminacion_plan')->label('Límite terminación')->date()->sortable(),
                TextColumn::make('fecha_limite_justificacion_plan')->label('Límite justificación')->date()->sortable(),
                TextColumn::make('fecha_limite_presentacion_proyecto')->label('Límite proyecto')->date(),
                TextColumn::make('fecha_limite_presentacion_documentacion')->label('Límite documentación')->date(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNormativaPpacs::route('/'),
            'create' => CreateNormativaPpac::route('/create'),
            'edit' => EditNormativaPpac::route('/{record}/edit'),
        ];
    }
}
