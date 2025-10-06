<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\ObraCedidaResource\Pages;
use App\Filament\Obras\Resources\ObraCedidaResource\RelationManagers;
use App\Models\ObraCedida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObraCedidaResource extends Resource
{
    protected static ?string $model = ObraCedida::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    public static function form(Form $form): Form
    {
        return $form
            ->columns(7)
            ->schema([
                Forms\Components\TextInput::make('Codigo_Plan')
                    ->required()
                    ->maxLength(7),
                Forms\Components\TextInput::make('numero_obra')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('FechaRemisionAyto'),
                Forms\Components\DateTimePicker::make('FechaRecepcionCerti'),
                Forms\Components\DateTimePicker::make('FechaCesion'),
                Forms\Components\DateTimePicker::make('FechaAdjudicacion'),
                Forms\Components\TextInput::make('ImporteAdjudicacion_Pts')
                    ->numeric(),
                Forms\Components\TextInput::make('NombreContratista')
                    ->maxLength(70),
                Forms\Components\TextInput::make('DomicilioContratista')
                    ->maxLength(80),
                Forms\Components\TextInput::make('CPostalContratista')
                    ->numeric(),
                Forms\Components\TextInput::make('Ciudad')
                    ->maxLength(50),
                Forms\Components\TextInput::make('CodMunContratista')
                    ->numeric(),
                Forms\Components\TextInput::make('NIFContratista')
                    ->maxLength(15),
                Forms\Components\DateTimePicker::make('FechaContrato'),
                Forms\Components\DateTimePicker::make('FechaRemisionInterv'),
                Forms\Components\TextInput::make('ImporteAdjudicacion')
                    ->numeric(),
                Forms\Components\TextInput::make('TelefContratista')
                    ->maxLength(50),
                Forms\Components\Toggle::make('NuevaLey')
                    ->required(),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Codigo_Plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_obra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FechaRemisionAyto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FechaRecepcionCerti')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FechaCesion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FechaAdjudicacion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ImporteAdjudicacion_Pts')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('NombreContratista')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DomicilioContratista')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CPostalContratista')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CodMunContratista')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('NIFContratista')
                    ->searchable(),
                Tables\Columns\TextColumn::make('FechaContrato')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FechaRemisionInterv')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ImporteAdjudicacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('TelefContratista')
                    ->searchable(),
                Tables\Columns\IconColumn::make('NuevaLey')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Expediente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListObraCedidas::route('/'),
            'create' => Pages\CreateObraCedida::route('/create'),
            'edit' => Pages\EditObraCedida::route('/{record}/edit'),
        ];
    }
}
