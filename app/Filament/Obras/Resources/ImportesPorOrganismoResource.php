<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\ImportesPorOrganismoResource\Pages;
use App\Filament\Obras\ResourcesImportesPorOrganismoResource\RelationManagers;
use App\Models\ImportesPorOrganismo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImportesPorOrganismoResource extends Resource
{
    protected static ?string $model = ImportesPorOrganismo::class;
    protected static ?string $navigationGroup="Importes";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Expediente')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('organismo')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('Porc_imp_aprobado')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('importe_aprobado')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('Porc_imp_contratar')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('Importe_a_contratar')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('Porc_imp_adjudicado')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('importe_adjudicacion')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('Porc_imp_baj')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('importe_baja_contratación')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('Expediente'),
                Tables\Columns\TextColumn::make('organismo'),
                Tables\Columns\TextColumn::make('importe_aprobado'),
                Tables\Columns\TextColumn::make('Porc_imp_contratar'),
                Tables\Columns\TextColumn::make('Importe_a_contratar'),
                Tables\Columns\TextColumn::make('Porc_imp_adjudicado'),
                Tables\Columns\TextColumn::make('importe_adjudicacion'),
                Tables\Columns\TextColumn::make('Porc_imp_baj'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
              //      Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListImportesPorOrganismos::route('/'),
            'create' => Pages\CreateImportesPorOrganismo::route('/create'),
            'edit' => Pages\EditImportesPorOrganismo::route('/{record}/edit'),
        ];
    }
}
