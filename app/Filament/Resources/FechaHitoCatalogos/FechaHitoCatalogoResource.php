<?php

declare(strict_types=1);

namespace App\Filament\Resources\FechaHitoCatalogos;

use App\Filament\Resources\FechaHitoCatalogos\Pages\CreateFechaHitoCatalogo;
use App\Filament\Resources\FechaHitoCatalogos\Pages\EditFechaHitoCatalogo;
use App\Filament\Resources\FechaHitoCatalogos\Pages\ListFechaHitoCatalogos;
use App\Models\FechaHitoCatalogo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FechaHitoCatalogoResource extends Resource
{
    protected static ?string $model = FechaHitoCatalogo::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Validacion de fechas';

    protected static ?string $navigationLabel = 'Catalogo de hitos';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('codigo_hito')
                ->label('Codigo de hito')
                ->required()
                ->maxLength(80),
            TextInput::make('descripcion')
                ->required()
                ->maxLength(200),
            TextInput::make('tabla_origen')
                ->required()
                ->maxLength(120),
            TextInput::make('campo_origen')
                ->required()
                ->maxLength(120),
            Select::make('fase')
                ->options([
                    'inicio' => 'Inicio',
                    'proyecto' => 'Proyecto',
                    'ejecucion' => 'Ejecucion',
                    'recepcion' => 'Recepcion',
                    'cesion' => 'Cesion',
                    'documental' => 'Documental',
                ])
                ->searchable(),
            TextInput::make('orden')
                ->numeric()
                ->minValue(1),
            Toggle::make('obligatorio')
                ->default(false),
            Toggle::make('repetible')
                ->default(false),
            Toggle::make('activa')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo_hito')->searchable()->sortable(),
                TextColumn::make('descripcion')->searchable()->wrap(),
                TextColumn::make('tabla_origen')->label('Tabla')->searchable(),
                TextColumn::make('campo_origen')->label('Campo')->searchable(),
                TextColumn::make('fase')->badge(),
                TextColumn::make('orden')->sortable(),
                IconColumn::make('obligatorio')->boolean(),
                IconColumn::make('repetible')->boolean(),
                IconColumn::make('activa')->boolean(),
            ])
            ->filters([
                SelectFilter::make('fase')
                    ->options([
                        'inicio' => 'Inicio',
                        'proyecto' => 'Proyecto',
                        'ejecucion' => 'Ejecucion',
                        'recepcion' => 'Recepcion',
                        'cesion' => 'Cesion',
                        'documental' => 'Documental',
                    ]),
                SelectFilter::make('activa')
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFechaHitoCatalogos::route('/'),
            'create' => CreateFechaHitoCatalogo::route('/create'),
            'edit' => EditFechaHitoCatalogo::route('/{record}/edit'),
        ];
    }
}
