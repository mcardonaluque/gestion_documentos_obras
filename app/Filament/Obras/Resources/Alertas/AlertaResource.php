<?php

namespace App\Filament\Obras\Resources\Alertas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\Alertas\Pages\ListAlertas;
use App\Filament\Obras\Resources\Alertas\Pages\CreateAlerta;
use App\Filament\Obras\Resources\Alertas\Pages\EditAlerta;
use App\Filament\Obras\Resources\AlertaResource\Pages;
use App\Filament\Obras\Resources\AlertaResource\RelationManagers;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'team';
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(510),
                TextInput::make('descripcion')
                    ->required()
                    ->maxLength(510),
                TextInput::make('tabla1')
                    ->required()
                    ->maxLength(510),
                TextInput::make('tabla2')
                    ->required()
                    ->maxLength(510),
                TextInput::make('campotabla1')
                    ->required()
                    ->maxLength(510),
                TextInput::make('campotabla2')
                    ->required()
                    ->maxLength(510),
                TextInput::make('condicion')
                    ->required()
                    ->maxLength(510),
                TextInput::make('mensaje')
                    ->required()
                    ->maxLength(510),
                Toggle::make('activa')
                    ->required(),
                TextInput::make('operacion')
                    ->required()
                    ->maxLength(510),
                TextInput::make('tipo')
                    ->required()
                    ->maxLength(510),
                TextInput::make('fase')
                    ->required()
                    ->maxLength(510),
                TextInput::make('estado')
                    ->maxLength(3),
                TextInput::make('accion')
                    ->required()
                    ->maxLength(510),
                TextInput::make('plazo')
                    ->required()
                    ->numeric(),
                TextInput::make('unidad_plazo')
                    ->required()
                    ->maxLength(2),
                Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->searchable(),
                TextColumn::make('tabla1')
                    ->searchable(),
                TextColumn::make('tabla2')
                    ->searchable(),
                TextColumn::make('campotabla1')
                    ->searchable(),
                TextColumn::make('campotabla2')
                    ->searchable(),
                TextColumn::make('condicion')
                    ->searchable(),
                TextColumn::make('mensaje')
                    ->searchable(),
                IconColumn::make('activa')
                    ->boolean(),
                TextColumn::make('operacion')
                    ->searchable(),
                TextColumn::make('tipo')
                    ->searchable(),
                TextColumn::make('fase')
                    ->searchable(),
                TextColumn::make('estado')
                    ->searchable(),
                TextColumn::make('accion')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('plazo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unidad_plazo')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
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
        return [
            //
        ];
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
