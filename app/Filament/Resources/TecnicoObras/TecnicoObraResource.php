<?php

namespace App\Filament\Resources\TecnicoObras;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TecnicoObras\Pages\ListTecnicoObras;
use App\Filament\Resources\TecnicoObras\Pages\CreateTecnicoObra;
use App\Filament\Resources\TecnicoObras\Pages\EditTecnicoObra;
use App\Filament\Resources\TecnicoObraResource\Pages;
use App\Filament\Resources\TecnicoObraResource\RelationManagers;
use App\Models\TecnicoObra;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TecnicoObraResource extends Resource
{
    protected static ?string $model = TecnicoObra::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('PJuridica')
                    ->required(),
                TextInput::make('OrgTec')
                    ->maxLength(2),
                TextInput::make('ServTec')
                    ->numeric(),
                TextInput::make('Zona')
                    ->maxLength(1),
                TextInput::make('CodAyto')
                    ->numeric(),
                TextInput::make('NombreTec')
                    ->maxLength(50),
                TextInput::make('Ape1Tec')
                    ->maxLength(50),
                TextInput::make('Ape2Tec')
                    ->maxLength(50),
                TextInput::make('Empresa')
                    ->maxLength(50),
                TextInput::make('Sexotec')
                    ->maxLength(1),
                TextInput::make('DniTec')
                    ->maxLength(15),
                TextInput::make('Codprofesion')
                    ->numeric(),
                TextInput::make('CodActividad')
                    ->numeric(),
                TextInput::make('DomTec')
                    ->maxLength(100),
                TextInput::make('LocTec')
                    ->maxLength(50),
                TextInput::make('CpTec')
                    ->maxLength(10),
                TextInput::make('MunTec')
                    ->numeric(),
                TextInput::make('MunicipioXX')
                    ->maxLength(50),
                TextInput::make('ProvTec')
                    ->numeric(),
                TextInput::make('ProvinciaXX')
                    ->maxLength(50),
                TextInput::make('TelTec')
                    ->maxLength(20),
                TextInput::make('TelTec2')
                    ->maxLength(20),
                TextInput::make('Movil')
                    ->maxLength(20),
                TextInput::make('Fax')
                    ->maxLength(20),
                TextInput::make('EmailTec')
                    ->maxLength(50),
                TextInput::make('DirectorLab')
                    ->maxLength(120),
                Toggle::make('Anulado'),
                TextInput::make('Observaciones')
                    ->maxLength(400),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('PJuridica')
                    ->boolean(),
                TextColumn::make('OrgTec')
                    ->searchable(),
                TextColumn::make('ServTec')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Zona')
                    ->searchable(),
                TextColumn::make('CodAyto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('NombreTec')
                    ->searchable(),
                TextColumn::make('Ape1Tec')
                    ->searchable(),
                TextColumn::make('Ape2Tec')
                    ->searchable(),
                TextColumn::make('Empresa')
                    ->searchable(),
                TextColumn::make('Sexotec')
                    ->searchable(),
                TextColumn::make('DniTec')
                    ->searchable(),
                TextColumn::make('Codprofesion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('CodActividad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('DomTec')
                    ->searchable(),
                TextColumn::make('LocTec')
                    ->searchable(),
                TextColumn::make('CpTec')
                    ->searchable(),
                TextColumn::make('MunTec')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('MunicipioXX')
                    ->searchable(),
                TextColumn::make('ProvTec')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ProvinciaXX')
                    ->searchable(),
                TextColumn::make('TelTec')
                    ->searchable(),
                TextColumn::make('TelTec2')
                    ->searchable(),
                TextColumn::make('Movil')
                    ->searchable(),
                TextColumn::make('Fax')
                    ->searchable(),
                TextColumn::make('EmailTec')
                    ->searchable(),
                TextColumn::make('DirectorLab')
                    ->searchable(),
                IconColumn::make('Anulado')
                    ->boolean(),
                TextColumn::make('Observaciones')
                    ->searchable(),
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
            'index' => ListTecnicoObras::route('/'),
            'create' => CreateTecnicoObra::route('/create'),
            'edit' => EditTecnicoObra::route('/{record}/edit'),
        ];
    }
}
