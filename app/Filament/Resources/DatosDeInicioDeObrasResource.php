<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatosDeInicioDeObrasResource\Pages;
use App\Filament\Resources\DatosDeInicioDeObrasResource\RelationManagers;
use App\Models\DatosDeInicioDeObras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DatosDeInicioDeObrasResource extends Resource
{
    protected static ?string $model = DatosDeInicioDeObras::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            Tables\Columns\TextColumn::make('Codigo_Plan')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('numero_obra')
                ->sortable()
                ->searchable(),
                
            Tables\Columns\TextColumn::make('subreferencia')
                ->searchable()
                ->hidden(),
            Tables\Columns\TextColumn::make('ao_ejecucion')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('nombre_obra1')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
            Tables\Columns\TextColumn::make('municipio.nombre_municipio')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
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
            'index' => Pages\ListDatosDeInicioDeObras::route('/'),
            'create' => Pages\CreateDatosDeInicioDeObras::route('/create'),
            'edit' => Pages\EditDatosDeInicioDeObras::route('/{record}/edit'),
        ];
    }
}
