<?php

namespace App\Filament\Obras\Resources\ActasDeReplanteo;

use App\Filament\Obras\Resources\ActasDeReplanteo\Pages\CreateActadereplanteo;
use App\Filament\Obras\Resources\ActasDeReplanteo\Pages\EditActadereplanteo;
use App\Filament\Obras\Resources\ActasDeReplanteo\Pages\ListActadereplanteos;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActadereplanteoResource extends Resource
{
    protected static ?string $model = \App\Models\actadereplanteo::class;

    protected static ?string $modelLabel = 'Acta de replanteo';

    protected static ?string $pluralModelLabel = 'Actas de replanteo';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = 'Ejecucion';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->required()
                    ->maxLength(50),
                DateTimePicker::make('Fecha_Inicio_Acta_Replanteo')
                    ->label('Fecha inicio'),
                DateTimePicker::make('Fecha_Final_Acta_Replanteo')
                    ->label('Fecha final'),
                DateTimePicker::make('Fecha_Prorroga_Acta_Replanteo')
                    ->label('Fecha prorroga'),
                Toggle::make('Indicador_Impresion_AR')
                    ->label('Impresion AR'),
                Toggle::make('Indicador_Recepcion_AR')
                    ->label('Recepcion AR'),
                TextInput::make('team_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('Fecha_Inicio_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Final_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Fecha_Prorroga_Acta_Replanteo')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('Indicador_Impresion_AR')
                    ->boolean(),
                IconColumn::make('Indicador_Recepcion_AR')
                    ->boolean(),
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
            'index' => ListActadereplanteos::route('/'),
            'create' => CreateActadereplanteo::route('/create'),
            'edit' => EditActadereplanteo::route('/{record}/edit'),
        ];
    }
}
