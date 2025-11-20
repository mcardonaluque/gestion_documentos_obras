<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\DocumentoexpedienteResource\Pages;
use App\Filament\Obras\Resources\DocumentoexpedienteResource\RelationManagers;
use App\Models\Documentoexpediente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoexpedienteResource extends Resource
{
    protected static ?string $model = DocumentoExpediente::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Documentos del Expediente';
    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cod_plan')
                    ->required()
                    ->maxLength(45),
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('fechaincorporacion')
                    ->required(),
                Forms\Components\DatePicker::make('fechaHelp'),
                Forms\Components\TextInput::make('cod_dcoumento')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('csv')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                Forms\Components\TextInput::make('nsecuencia')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('estado.nombre')
                    ->required(),
                   
                Forms\Components\TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
                Forms\Components\TextInput::make('destino.destino')
                    ->label('Destino'),
                 
                Forms\Components\TextInput::make('procedencia.destino')
                    ->label('Procedencia')
                   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
       
            ->columns([
                Tables\Columns\TextColumn::make('cod_plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fechaincorporacion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fechaHelp')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cod_dcoumento')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expediente_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('csv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nregistro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nsecuencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destinos.destino')
                    ->label('Destino')
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedencias.destino')
                   ->label('Procedencia')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make('Ver Documento')
                ->url(function ($record){
                    $tenant = \Filament\Facades\Filament::getTenant();
                     route('filament.ayuntamientos.resources.documentoexpedientes.view', ['record' => $record,'tenant' => $tenant]);
                }),
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
            'index' => Pages\ListDocumentoexpedientes::route('/'),
            'create' => Pages\CreateDocumentoexpediente::route('/create'),
            'edit' => Pages\EditDocumentoexpediente::route('/{record}/edit'),
            'view' => Pages\ViewDocumentoexpediente::route('/{record}'),
        ];
    }
}
