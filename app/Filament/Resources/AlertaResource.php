<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertaResource\Pages;
use App\Filament\Resources\AlertaResource\RelationManagers;
use App\Models\Alerta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlertaResource extends Resource
{
    protected static ?string $model = Alerta::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-bell';
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
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descripcion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tabla1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tabla2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('campotabla1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('campotabla2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('condicion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mensaje')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('activa')
                    ->required(),
                Forms\Components\Select::make('operacion')
                    ->options([
                        'Modificar' => 'Modificar',
                        'Insertar' => 'Insertar',
                    ])
                    ->required(),
                Forms\Components\Select::make('tipo')
                    ->options([
                        'Advertencia' => 'Advertencia',
                        'Alerta' => 'Alerta',
                        'Error' => 'Error',
                    ])
                    ->required(),
                Forms\Components\Select::make('fase')
                    ->relationship('faseRelacionada', 'nombre') // Relación en el modelo Alerta
                    ->required(),
                Forms\Components\Select::make('estado')
                    ->relationship('estadoRelacionado', 'estado') // Relación en el modelo Alerta
                    ->required(),
                Forms\Components\Select::make('accion')
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
                Tables\Columns\TextColumn::make('nombre')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('descripcion')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tabla1')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tabla2')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('campotabla1')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('campotabla2')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('condicion')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mensaje')->sortable()->searchable(),
                Tables\Columns\CheckboxColumn::make('activa')->sortable(),
                Tables\Columns\TextColumn::make('operacion')->sortable(),
                Tables\Columns\TextColumn::make('tipo')->sortable(),
                Tables\Columns\TextColumn::make('fase')->sortable(),
                Tables\Columns\TextColumn::make('estado')->sortable(),
                Tables\Columns\TextColumn::make('accion')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                // Filtros opcionales
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlertas::route('/'),
            'create' => Pages\CreateAlerta::route('/create'),
            'edit' => Pages\EditAlerta::route('/{record}/edit'),
        ];
    }
}