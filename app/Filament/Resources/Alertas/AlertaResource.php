<?php

namespace App\Filament\Resources\Alertas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\Alertas\Pages\ListAlertas;
use App\Filament\Resources\Alertas\Pages\CreateAlerta;
use App\Filament\Resources\Alertas\Pages\EditAlerta;
use App\Filament\Resources\AlertaResource\Pages;
use App\Filament\Resources\AlertaResource\RelationManagers;
use App\Models\Alerta;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlertaResource extends Resource
{
    protected static ?string $model = Alerta::class;
    protected static string | \UnitEnum | null $navigationGroup="Notificaciones";
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell';
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('descripcion')
                    ->required()
                    ->maxLength(255),
                TextInput::make('tabla1')
                    ->required()
                    ->maxLength(255),
                TextInput::make('tabla2')
                    ->required()
                    ->maxLength(255),
                TextInput::make('campotabla1')
                    ->required()
                    ->maxLength(255),
                TextInput::make('campotabla2')
                    ->required()
                    ->maxLength(255),
                TextInput::make('condicion')
                    ->required()
                    ->maxLength(255),
                TextInput::make('mensaje')
                    ->required()
                    ->maxLength(255),
                Toggle::make('activa')
                    ->required(),
                Select::make('operacion')
                    ->options([
                        'Modificar' => 'Modificar',
                        'Insertar' => 'Insertar',
                    ])
                    ->required(),
                Select::make('tipo')
                    ->options([
                        'Advertencia' => 'Advertencia',
                        'Alerta' => 'Alerta',
                        'Error' => 'Error',
                    ])
                    ->required(),
                Select::make('fase')
                    ->relationship('faseRelacionada', 'nombre') // Relación en el modelo Alerta
                    ->required(),
                Select::make('estado')
                    ->relationship('estadoRelacionado', 'estado') // Relación en el modelo Alerta
                    ->required(),
                Select::make('accion')
                    ->options([
                        'stop' => 'Stop',
                        'continue' => 'Continue',
                        'notificación'=>'Notificación',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->sortable()->searchable(),
                TextColumn::make('descripcion')->sortable()->searchable(),
                TextColumn::make('tabla1')->sortable()->searchable(),
                TextColumn::make('tabla2')->sortable()->searchable(),
                TextColumn::make('campotabla1')->sortable()->searchable(),
                TextColumn::make('campotabla2')->sortable()->searchable(),
                TextColumn::make('condicion')->sortable()->searchable(),
                TextColumn::make('mensaje')->sortable()->searchable(),
                CheckboxColumn::make('activa')->sortable(),
                TextColumn::make('operacion')->sortable(),
                TextColumn::make('tipo')->sortable(),
                TextColumn::make('fase')->sortable(),
                TextColumn::make('estado')->sortable(),
                TextColumn::make('accion')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                // Filtros opcionales
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlertas::route('/'),
            'create' => CreateAlerta::route('/create'),
            'edit' => EditAlerta::route('/{record}/edit'),
        ];
    }
}
