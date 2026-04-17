<?php

namespace App\Filament\Resources\DocumentoGenericos\RelationManagers;

use App\Enums\TemplateVariableSourceType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariablesRelationManager extends RelationManager
{
    protected static string $relationship = 'variables';

    protected static ?string $title = 'Variables de plantilla';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('variable')
                    ->label('Variable')
                    ->required()
                    ->maxLength(120),
                Select::make('source_type')
                    ->label('Origen')
                    ->options(TemplateVariableSourceType::options())
                    ->required()
                    ->default(TemplateVariableSourceType::AUTO->value),
                TextInput::make('source_path')
                    ->label('Ruta lógica')
                    ->helperText('Ejemplo: planes.denominacion_plan o expediente_id'),
                TextInput::make('format')
                    ->label('Formato')
                    ->helperText('Ejemplos: date:d/m/Y o number:2'),
                TextInput::make('default_value')
                    ->label('Valor por defecto'),
                Toggle::make('is_required')
                    ->label('Obligatoria')
                    ->default(true),
                Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('variable')
            ->columns([
                TextColumn::make('variable')
                    ->label('Variable')
                    ->searchable(),
                TextColumn::make('source_type')
                    ->label('Origen'),
                TextColumn::make('source_path')
                    ->label('Ruta lógica')
                    ->searchable(),
                TextColumn::make('format')
                    ->label('Formato'),
                IconColumn::make('is_required')
                    ->label('Obligatoria')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
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
