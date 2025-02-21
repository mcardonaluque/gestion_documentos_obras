<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentoGenericoResource\Pages;
use App\Filament\Resources\DocumentoGenericoResource\RelationManagers;
use App\Models\DestinoDeDocumentos;
use App\Models\DocumentoGenerico;
use App\Models\FaseDocumento;
use App\Models\TablaDeEstados;
use App\Models\TipoDocumento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoGenericoResource extends Resource
{

    protected static ?string $model = DocumentoGenerico::class;
  
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Documentación';
    protected static ?string $navigationLabel = 'Documentos genéricos';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('nombreDocumento')
                    ->required(),
                Forms\Components\Textarea::make('Descricpcion')
                    ->required(),
                Forms\Components\Select::make('fase_doc')
                    //->relationship(name : 'fasedoc', titleAttribute:'nombre')
                    ->options(FaseDocumento::All()->pluck('nombre','cod_fase')->toArray())
                    ->label('Fase')
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Select::make('fase_siguiente')
                    //->relationship(name : 'fasedoc', titleAttribute:'nombre')
                    ->options(FaseDocumento::All()->pluck('nombre','cod_fase')->toArray())
                    ->label('Fase Siguiente')
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Select::make('cod_tipo_doc')
                    //->relationship(name : 'tipodoc', titleAttribute:'nombre')
                    ->label('Tipo de documento')
                    ->options(TipoDocumento::All()->pluck('nombre','IdTipo')->toArray())
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Checkbox::make('con_plantilla')
                    ->required()
                    ->reactive()
                    ->default(true),
                    //->afterStateUpdated(function ($state, callable $set){
                    //    $set('plantilla',  $state ? 'visible' : 'hidden');
                    //    $set('rutaPlantilla',  $state ? 'visible' : 'hidden');
                    //}),
                Forms\Components\TextInput::make('plantilla')
                    ->visible(fn (Get $get): bool =>  $get('con_plantilla'))
                    ->label('Plantilla')
                    ->autocomplete(true),
                 Forms\Components\TextInput::make('rutaPlantilla')
                    ->visible(fn (Get $get): bool =>  $get('con_plantilla'))
                    ->url ()
                    ->autocomplete(true),
                Forms\Components\Checkbox::make('obligatorio')
                    ->required()
                    ->default(true),
                Forms\Components\Select::make('cod_estado')
                    //->relationship(name : 'tipodoc', titleAttribute:'nombre')
                    ->label('Estado del expediente')
                    ->options(TablaDeEstados::All()->pluck('estado','cod_estado')->toArray())
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Select::make('entrada_salida')
                    ->label('Entrada/Salida')
                    ->options(['Entrada'=>'Entrada',
                                        'Salida'=>'Salida']),
                Forms\Components\Select::make('cod_estado')
                                        //->relationship(name : 'tipodoc', titleAttribute:'nombre')
                    ->label('Estado del expediente')
                    ->options(DestinoDeDocumentos::All()->pluck('destino','id')->toArray())
                    ->searchable()
                    ->preload()
                   ->live(),                        
                        
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        
        return $table
            ->columns([
                //
            Tables\Columns\TextColumn::make('nombreDocumento')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('descripcion')
                ->sortable()
                ->searchable(),
                
            Tables\Columns\TextColumn::make('tipoDocumento.name')
                ->searchable(),
            Tables\Columns\TextColumn::make('faseDocumento.name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('descripcion')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                //SelectFilter::make('tipo')
                //    ->relationship('tipoDocumento', 'tipodocumento')
                //    ->searchable()
                //    ->preload()
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
            'index' => Pages\ListDocumentoGenericos::route('/'),
            'create' => Pages\CreateDocumentoGenerico::route('/create'),
            'edit' => Pages\EditDocumentoGenerico::route('/{record}/edit'),
        ];
    }
}
