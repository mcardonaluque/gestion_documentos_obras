<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
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
use App\Models\AyudaTecnica;

class AyudaRelationManager extends RelationManager
{
    protected static string $relationship = 'ayudaTecnica';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('expediente_id')
                    ->default(fn () => $this->ownerRecord?->expediente_id)
                    ->afterStateHydrated(function (TextInput $component, $state): void {
                        if (blank($state)) {
                            $component->state($this->ownerRecord?->expediente_id);
                        }
                    })
                    ->readOnly()
                    ->dehydrated(true)
                    ->maxLength(255),
                Select::make('dpto_redactor')
                    ->label('Departamento Redactor')
                    ->relationship('ayudaR', 'DENOMINACION')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        if (filled($state)) {
                            $set('AyuTecRed', 'SI');
                        }
                    }),

                Select::make('departamento_direccion')
                    ->label('Departamento Dirección')
                    ->relationship('ayudaD', 'DENOMINACION')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        if (filled($state)) {
                            $set('AyuTecDir', 'SI');
                        }
                    }),

                TextInput::make('AyuTecRed')
                    ->required()
                    //->disabled()
                    ->maxLength(255),
                TextInput::make('AyuTecDir')
                    ->required()
                    //->disabled()
                    ->maxLength(255),
                TextInput::make('SubvencionEconomicaR')
                    //->required()
                    //->disabled()
                    ->maxLength(255),
                TextInput::make('SubvencionEconomicaD')
                    //->required()
                    //->disabled()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('expediente_id'),
                TextColumn::make('dpto_redactor'),
                TextColumn::make('departamento_direcccion'),
                TextColumn::make('AyuTecRed'),
                TextColumn::make('AyuTecDir'),
                TextColumn::make('SubvencionEconomicaR'),
                TextColumn::make('SubvencionEconomicaD'),
                TextColumn::make('codigo_municipio'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                ->visible(fn() =>!AyudaTecnica::exists()),
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
