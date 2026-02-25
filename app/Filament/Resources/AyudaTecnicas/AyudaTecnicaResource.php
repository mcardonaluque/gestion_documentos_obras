<?php

namespace App\Filament\Resources\AyudaTecnicas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\AyudaTecnicas\Pages\ListAyudaTecnicas;
use App\Filament\Resources\AyudaTecnicas\Pages\CreateAyudaTecnica;
use App\Filament\Resources\AyudaTecnicas\Pages\EditAyudaTecnica;
use App\Filament\Resources\AyudaTecnicaResource\Pages;
use App\Filament\Resources\AyudaTecnicaResource\RelationManagers;
use App\Models\AyudaTecnica;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AyudaTecnicaResource extends Resource
{
    protected static ?string $model = AyudaTecnica::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
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
                TextInput::make('departamento')
                    ->numeric(),
                Select::make('municipio')
                    ->label('Municipio')
                    ->relationship('municipios', 'nombre_municipio')
                    ->disabled()
                    //->hidden()
                    ->extraAttributes(['class' => 'custom-select-class'])
                    ->visible(fn ($get) => $get('municipio'))
                    ->reactive()    , // Hace que el campo sea reactivo
                TextInput::make('ao_proyecto')
                    ->numeric(),
                TextInput::make('numero_proyecto')
                    ->numeric(),
                Select::make('ayuda.dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayuda.ayudaR', 'DENOMINACION'),

                Select::make('ayuda.departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayuda.ayudaD', 'DENOMINACION'),

                Toggle::make('pasado')
                    ->required(),
                TextInput::make('SubvencionEconomicaR')
                    ->maxLength(2),
                TextInput::make('SubvencionEconomicaD')
                    ->maxLength(2),
                TextInput::make('AyuTecRed')
                    ->maxLength(2),
                TextInput::make('AyuTecDir')
                    ->maxLength(2),
                TextInput::make('team_id')
                    ->numeric(),
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
                TextColumn::make('departamento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('codigo_municipio')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_proyecto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('numero_proyecto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dpto_redactor')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('departamento_direccion')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('pasado')
                    ->boolean(),
                TextColumn::make('SubvencionEconomicaR')
                    ->searchable(),
                TextColumn::make('SubvencionEconomicaD')
                    ->searchable(),
                TextColumn::make('AyuTecRed')
                    ->searchable(),
                TextColumn::make('AyuTecDir')
                    ->searchable(),
                TextColumn::make('expediente_id')
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
            'index' => ListAyudaTecnicas::route('/'),
            'create' => CreateAyudaTecnica::route('/create'),
            'edit' => EditAyudaTecnica::route('/{record}/edit'),
        ];
    }
}
