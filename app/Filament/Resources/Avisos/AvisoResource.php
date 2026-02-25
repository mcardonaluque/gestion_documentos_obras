<?php

namespace App\Filament\Resources\Avisos;

use App\Filament\Resources\Avisos\Pages\ListAvisos;
use App\Filament\Resources\Avisos\Pages\CreateAviso;
use App\Filament\Resources\Avisos\Pages\EditAviso;
use App\Filament\Resources\AvisoResource\Pages;
use App\Filament\Resources\AvisoResource\RelationManagers;
use App\Models\Aviso;
use App\Models\TiposAviso;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvisoResource extends Resource
{
    protected static ?string $model = Aviso::class;
    //protected static bool $isScopedToTenant = false;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static string | \UnitEnum | null $navigationGroup="Notificaciones";
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Referencia')
                    ->required()
                    ->maxLength(50),
                Select::make('TipoAviso')
                    ->label('Tipo de Aviso')
                    ->relationship('tipodeaviso', 'Des') // Usar la relación para obtener los tipos de aviso
                   // ->options(TiposAviso::all()->pluck('Des', 'TipoAviso')) // Obtener los tipos de aviso
                    ->required(),
                TextInput::make('Codigo_Plan')
                    ->maxLength(7),
                TextInput::make('numero_obra')
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->numeric(),
                TextInput::make('Usuario')
                    ->maxLength(20),
                DateTimePicker::make('FecSolucion'),
                Toggle::make('borrado')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Referencia')->sortable()->searchable(),
                TextColumn::make('tipodeaviso.Des')->label('Tipo de Aviso')->sortable(), // Mostrar el nombre del tipo de aviso
                TextColumn::make('Codigo_Plan')->sortable()->searchable(),
                TextColumn::make('numero_obra')->sortable(),
                TextColumn::make('subreferencia')->sortable(),
                TextColumn::make('ao_ejecucion')->sortable(),
                TextColumn::make('Usuario')->sortable()->searchable(),
                TextColumn::make('FecSolucion')->dateTime()->sortable(),
                IconColumn::make('borrado')->boolean()->sortable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAvisos::route('/'),
            'create' => CreateAviso::route('/create'),
            'edit' => EditAviso::route('/{record}/edit'),
        ];
    }
}
