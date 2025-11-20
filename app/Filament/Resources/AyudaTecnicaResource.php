<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AyudaTecnicaResource\Pages;
use App\Filament\Resources\AyudaTecnicaResource\RelationManagers;
use App\Models\AyudaTecnica;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AyudaTecnicaResource extends Resource
{
    protected static ?string $model = AyudaTecnica::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
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
                Forms\Components\TextInput::make('departamento')
                    ->numeric(),
                Forms\Components\Select::make('municipio')
                    ->label('Municipio')
                    ->relationship('municipios', 'nombre_municipio')
                    ->disabled()
                    //->hidden()
                    ->extraAttributes(['class' => 'custom-select-class'])
                    ->visible(fn ($get) => $get('municipio'))
                    ->reactive()    , // Hace que el campo sea reactivo
                Forms\Components\TextInput::make('ao_proyecto')
                    ->numeric(),
                Forms\Components\TextInput::make('numero_proyecto')
                    ->numeric(),
                Forms\Components\Select::make('ayuda.dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayuda.ayudaR', 'DENOMINACION'),

                Forms\Components\Select::make('ayuda.departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayuda.ayudaD', 'DENOMINACION'),

                Forms\Components\Toggle::make('pasado')
                    ->required(),
                Forms\Components\TextInput::make('SubvencionEconomicaR')
                    ->maxLength(2),
                Forms\Components\TextInput::make('SubvencionEconomicaD')
                    ->maxLength(2),
                Forms\Components\TextInput::make('AyuTecRed')
                    ->maxLength(2),
                Forms\Components\TextInput::make('AyuTecDir')
                    ->maxLength(2),
                Forms\Components\TextInput::make('team_id')
                    ->numeric(),
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
                Tables\Columns\TextColumn::make('departamento')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('codigo_municipio')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_proyecto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_proyecto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dpto_redactor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departamento_direccion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('pasado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('SubvencionEconomicaR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('SubvencionEconomicaD')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AyuTecRed')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AyuTecDir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expediente_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team_id')
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
            'index' => Pages\ListAyudaTecnicas::route('/'),
            'create' => Pages\CreateAyudaTecnica::route('/create'),
            'edit' => Pages\EditAyudaTecnica::route('/{record}/edit'),
        ];
    }
}
