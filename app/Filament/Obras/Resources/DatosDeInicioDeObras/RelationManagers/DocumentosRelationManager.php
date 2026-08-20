<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObras\RelationManagers;

use App\Models\DocumentoGenerico;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'documentos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cod_plan')
                ->required()
                ->maxLength(45),
            TextInput::make('referencia')
                ->required()
                ->numeric(),
            TextInput::make('subreferencia')
                ->numeric()
                ->default(null),
            TextInput::make('ao_ejecucion')
                ->required()
                ->numeric(),
            DatePicker::make('fechaincorporacion')
                ->required(),
            DatePicker::make('fechaHelp'),
            Select::make('cod_documento')
                ->label('Tipo de documento')
                ->options(fn (): array => DocumentoGenerico::query()
                    ->orderBy('nombre')
                    ->pluck('nombre', 'id')
                    ->all())
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function ($state, callable $set): void {
                    $documentType = DocumentoGenerico::query()->find($state);

                    $set('destino', $documentType?->cod_destino ?: null);
                    $set('procedencia', $documentType?->cod_origen ?: null);
                })
                ->required(),
            TextInput::make('csv')
                ->maxLength(50)
                ->default(null),
            TextInput::make('nregistro')
                ->maxLength(45)
                ->default(null),
            TextInput::make('nsecuencia')
                ->numeric()
                ->default(null),
            TextInput::make('estado')
                ->required()
                ->numeric(),
            TextInput::make('descripcion')
                ->maxLength(255)
                ->default(null),
            TextInput::make('destino')
                ->numeric(),
            TextInput::make('procedencia')
                ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('cod_plan')
                ->searchable(),
            TextColumn::make('referencia')
                ->numeric()
                ->sortable(),
            TextColumn::make('subreferencia')
                ->numeric()
                ->sortable(),
            TextColumn::make('ao_ejecucion')
                ->numeric()
                ->sortable(),
            TextColumn::make('fechaincorporacion')
                ->date()
                ->sortable(),
            TextColumn::make('fechaHelp')
                ->date()
                ->sortable(),
            TextColumn::make('tipodocumentos.nombre')
                ->label('Tipo de documento')
                ->sortable(),
            TextColumn::make('expediente_id')
                ->searchable(),
            TextColumn::make('csv')
                ->searchable(),
            TextColumn::make('nregistro')
                ->searchable(),
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
