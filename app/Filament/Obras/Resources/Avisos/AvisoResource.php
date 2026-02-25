<?php

namespace App\Filament\Obras\Resources\Avisos;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\Avisos\Pages\ListAvisos;
use App\Filament\Obras\Resources\Avisos\Pages\CreateAviso;
use App\Filament\Obras\Resources\Avisos\Pages\EditAviso;
use App\Filament\Obras\Resources\AvisoResource\Pages;
use App\Filament\Obras\Resources\AvisoResource\RelationManagers;
use App\Models\Aviso;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvisoResource extends Resource
{
    protected static ?string $model = Aviso::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Referencia')
                    ->required()
                    ->maxLength(50),
                TextInput::make('TipoAviso')
                    ->required()
                    ->numeric(),
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
                    ->required(),
                TextInput::make('Expediente')
                    ->maxLength(100),
                TextInput::make('team_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Referencia')
                    ->searchable(),
                TextColumn::make('TipoAviso')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Codigo_Plan')
                    ->searchable(),
                TextColumn::make('numero_obra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Usuario')
                    ->searchable(),
                TextColumn::make('FecSolucion')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('borrado')
                    ->boolean(),
                TextColumn::make('Expediente')
                    ->searchable(),
                TextColumn::make('team_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListAvisos::route('/'),
            'create' => CreateAviso::route('/create'),
            'edit' => EditAviso::route('/{record}/edit'),
        ];
    }
}
