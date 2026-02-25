<?php

namespace App\Filament\Shared\Resources\Documentoexpedientes;

use App\Models\DocumentoExpediente;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

abstract class DocumentoexpedienteResourceBase extends Resource
{
    protected static ?string $model = DocumentoExpediente::class;

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                  Select::make('cod_plan')
                    ->relationship('planes', 'codigo_plan')
                    ->label('Código plan')
                    ->disabled()
                    ->default(fn () => $this->getOwnerRecord()?->codigo_plan)
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
                    ->relationship('tipodocumentos', 'nombre')
                    ->required()
                    ->numeric(),
                TextInput::make('csv')
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                TextInput::make('nsecuencia')
                    ->numeric()
                    ->default(null),
                 Select::make('cod_estado')
                    ->relationship('estados', 'nombre')
                    ->required(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
                Select::make('team_id')
                    ->relationship('team', 'name'),
                Select::make('destino')
                    ->relationship('destinos', 'destino')
                    ->label('Destino'),
                Select::make('procedencia')
                    ->relationship('procedencias', 'destino')
                    ->label('Procedencia'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cod_plan')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('referencia')
                    ->numeric()
                    ->hidden()
                    ->sortable(),
                TextColumn::make('subreferencia')
                    ->hidden()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->hidden()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('fechaHelp')
                    ->date()
                    ->sortable(),
                TextColumn::make('cod_dcoumento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('csv')
                    ->searchable(),
                TextColumn::make('nregistro')
                    ->searchable(),
                TextColumn::make('nsecuencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado')
                    ->relationship('estado', 'nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('destinos.destino')
                    ->label('Destino')
                    ->sortable(),
                TextColumn::make('procedencias.destino')
                    ->label('Procedencia')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make('Ver Documento')
                    ->url(fn ($record): string => static::getUrl('view', ['record' => $record])),
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
}
