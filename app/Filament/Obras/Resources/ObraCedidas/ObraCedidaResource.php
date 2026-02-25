<?php

namespace App\Filament\Obras\Resources\ObraCedidas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\ObraCedidas\Pages\ListObraCedidas;
use App\Filament\Obras\Resources\ObraCedidas\Pages\CreateObraCedida;
use App\Filament\Obras\Resources\ObraCedidas\Pages\EditObraCedida;
use App\Filament\Obras\Resources\ObraCedidaResource\Pages;
use App\Filament\Obras\Resources\ObraCedidaResource\RelationManagers;
use App\Models\ObraCedida;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObraCedidaResource extends Resource
{
    protected static ?string $model = ObraCedida::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(7)
            ->components([
                TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(7),
                TextInput::make('numero_obra')
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('FechaRemisionAyto'),
                DateTimePicker::make('FechaRecepcionCerti'),
                DateTimePicker::make('FechaCesion'),
                DateTimePicker::make('FechaAdjudicacion'),
                TextInput::make('ImporteAdjudicacion_Pts')
                    ->numeric(),
                TextInput::make('NombreContratista')
                    ->maxLength(70),
                TextInput::make('DomicilioContratista')
                    ->maxLength(80),
                TextInput::make('CPostalContratista')
                    ->numeric(),
                TextInput::make('Ciudad')
                    ->maxLength(50),
                TextInput::make('CodMunContratista')
                    ->numeric(),
                TextInput::make('NIFContratista')
                    ->maxLength(15),
                DateTimePicker::make('FechaContrato'),
                DateTimePicker::make('FechaRemisionInterv'),
                TextInput::make('ImporteAdjudicacion')
                    ->numeric(),
                TextInput::make('TelefContratista')
                    ->maxLength(50),
                Toggle::make('NuevaLey')
                    ->required(),
                Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('FechaRemisionAyto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaRecepcionCerti')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaCesion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaAdjudicacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ImporteAdjudicacion_Pts')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('NombreContratista')
                    ->searchable(),
                TextColumn::make('DomicilioContratista')
                    ->searchable(),
                TextColumn::make('CPostalContratista')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Ciudad')
                    ->searchable(),
                TextColumn::make('CodMunContratista')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('NIFContratista')
                    ->searchable(),
                TextColumn::make('FechaContrato')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('FechaRemisionInterv')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ImporteAdjudicacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('TelefContratista')
                    ->searchable(),
                IconColumn::make('NuevaLey')
                    ->boolean(),
                TextColumn::make('Expediente')
                    ->searchable(),
                TextColumn::make('team.name')
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
                    // DeleteBulkAction::make(),
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
            'index' => ListObraCedidas::route('/'),
            'create' => CreateObraCedida::route('/create'),
            'edit' => EditObraCedida::route('/{record}/edit'),
        ];
    }
}
