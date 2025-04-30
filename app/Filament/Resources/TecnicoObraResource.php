<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TecnicoObraResource\Pages;
use App\Filament\Resources\TecnicoObraResource\RelationManagers;
use App\Models\TecnicoObra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TecnicoObraResource extends Resource
{
    protected static ?string $model = TecnicoObra::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('PJuridica')
                    ->required(),
                Forms\Components\TextInput::make('OrgTec')
                    ->maxLength(2),
                Forms\Components\TextInput::make('ServTec')
                    ->numeric(),
                Forms\Components\TextInput::make('Zona')
                    ->maxLength(1),
                Forms\Components\TextInput::make('CodAyto')
                    ->numeric(),
                Forms\Components\TextInput::make('NombreTec')
                    ->maxLength(50),
                Forms\Components\TextInput::make('Ape1Tec')
                    ->maxLength(50),
                Forms\Components\TextInput::make('Ape2Tec')
                    ->maxLength(50),
                Forms\Components\TextInput::make('Empresa')
                    ->maxLength(50),
                Forms\Components\TextInput::make('Sexotec')
                    ->maxLength(1),
                Forms\Components\TextInput::make('DniTec')
                    ->maxLength(15),
                Forms\Components\TextInput::make('Codprofesion')
                    ->numeric(),
                Forms\Components\TextInput::make('CodActividad')
                    ->numeric(),
                Forms\Components\TextInput::make('DomTec')
                    ->maxLength(100),
                Forms\Components\TextInput::make('LocTec')
                    ->maxLength(50),
                Forms\Components\TextInput::make('CpTec')
                    ->maxLength(10),
                Forms\Components\TextInput::make('MunTec')
                    ->numeric(),
                Forms\Components\TextInput::make('MunicipioXX')
                    ->maxLength(50),
                Forms\Components\TextInput::make('ProvTec')
                    ->numeric(),
                Forms\Components\TextInput::make('ProvinciaXX')
                    ->maxLength(50),
                Forms\Components\TextInput::make('TelTec')
                    ->maxLength(20),
                Forms\Components\TextInput::make('TelTec2')
                    ->maxLength(20),
                Forms\Components\TextInput::make('Movil')
                    ->maxLength(20),
                Forms\Components\TextInput::make('Fax')
                    ->maxLength(20),
                Forms\Components\TextInput::make('EmailTec')
                    ->maxLength(50),
                Forms\Components\TextInput::make('DirectorLab')
                    ->maxLength(120),
                Forms\Components\Toggle::make('Anulado'),
                Forms\Components\TextInput::make('Observaciones')
                    ->maxLength(400),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('PJuridica')
                    ->boolean(),
                Tables\Columns\TextColumn::make('OrgTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ServTec')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Zona')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CodAyto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('NombreTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Ape1Tec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Ape2Tec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Empresa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Sexotec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DniTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Codprofesion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CodActividad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('DomTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('LocTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CpTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MunTec')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('MunicipioXX')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ProvTec')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ProvinciaXX')
                    ->searchable(),
                Tables\Columns\TextColumn::make('TelTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('TelTec2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Movil')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fax')
                    ->searchable(),
                Tables\Columns\TextColumn::make('EmailTec')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DirectorLab')
                    ->searchable(),
                Tables\Columns\IconColumn::make('Anulado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Observaciones')
                    ->searchable(),
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
            'index' => Pages\ListTecnicoObras::route('/'),
            'create' => Pages\CreateTecnicoObra::route('/create'),
            'edit' => Pages\EditTecnicoObra::route('/{record}/edit'),
        ];
    }
}
