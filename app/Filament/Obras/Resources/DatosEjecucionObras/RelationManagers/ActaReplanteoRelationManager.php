<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActaReplanteoRelationManager extends RelationManager
{
    protected static string $relationship = 'actaReplanteo';

    protected static ?string $title = 'Acta de Replanteo';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DateTimePicker::make('Fecha_Inicio_Acta_Replanteo'),
                DateTimePicker::make('Fecha_Final_Acta_Replanteo'),
                DateTimePicker::make('Fecha_Prorroga_Acta_Replanteo'),
                Toggle::make('Indicador_Impresion_AR'),
                Toggle::make('Indicador_Recepcion_AR'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('Fecha_Inicio_Acta_Replanteo')->dateTime()->label('Fecha inicio'),
                TextColumn::make('Fecha_Final_Acta_Replanteo')->dateTime()->label('Fecha final'),
                TextColumn::make('Fecha_Prorroga_Acta_Replanteo')->dateTime()->label('Fecha prórroga'),
                IconColumn::make('Indicador_Impresion_AR')->boolean()->label('Impresión'),
                IconColumn::make('Indicador_Recepcion_AR')->boolean()->label('Recepción'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['expediente_id'] = $this->getOwnerRecord()->expediente_id;
                        $data['team_id'] = $this->getOwnerRecord()->team_id;

                        return $data;
                    })
                    ->hidden(fn (): bool => filled($this->getRelationship()->first())),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
