<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImportesPorOrganismoRelationManager extends RelationManager
{
    protected static string $relationship = 'importesPorOrganismo';
    protected function getInverseRelationship(): string
    {
        return 'obra'; // esto debe ser el nombre de la relación inversa en el modelo ImportesporOrganismo
    }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('expediente_id')
                    ->required()
                    ->maxLength(255),
                TextInput::make('organismo')
                    ->required()
                    ->maxLength(255),
                TextInput::make('Porc_imp_aprobado')
                    ->required()
                    ->maxLength(255),
                TextInput::make('importe_aprobado')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('organismo'),
                TextColumn::make('Porc_imp_aprobado'),
                TextColumn::make('importe_aprobado'),
                TextColumn::make('Porc_importe_contratar'),
                TextColumn::make('importe_a_contratar'),
                TextColumn::make('Porc_imp_adjudicado'),
                TextColumn::make('importe_adjudicacion'),
                TextColumn::make('Porc_imp_baja'),
                TextColumn::make('importe_baja_contratacion'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
