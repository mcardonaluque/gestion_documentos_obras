<?php

namespace App\Filament\Resources\DocumentoGenericos;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DocumentoGenericos\Pages\ListDocumentoGenericos;
use App\Filament\Resources\DocumentoGenericos\Pages\CreateDocumentoGenerico;
use App\Filament\Resources\DocumentoGenericos\Pages\EditDocumentoGenerico;
use App\Filament\Resources\DocumentoGenericoResource\Pages;
use App\Filament\Resources\DocumentoGenericoResource\RelationManagers;
use App\Models\DestinoDeDocumentos;
use App\Models\DocumentoGenerico;
use App\Models\FaseDocumento;
use App\Models\TablaDeEstados;
use App\Models\TipoDocumento;
use Filament\Forms;
use Filament\Schemas\Schema;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Documentación';
    protected static ?string $navigationLabel = 'Documentos genéricos';
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('nombre')
                ->label('Nombre del documento')
                ->visible()
                ->required(),
                Textarea::make('descripcion')
                    ->required(),
                Select::make('fase_doc')
                    //->relationship(name : 'fasedoc', titleAttribute:'nombre')
                    ->options(FaseDocumento::All()->pluck('nombre','cod_fase')->toArray())
                    ->label('Fase')
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('fase_siguiente')
                    //->relationship(name : 'fasedoc', titleAttribute:'nombre')
                    ->options(FaseDocumento::All()->pluck('nombre','cod_fase')->toArray())
                    ->label('Fase Siguiente')
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('cod_tipo_doc')
                    //->relationship(name : 'tipodoc', titleAttribute:'nombre')
                    ->label('Tipo de documento')
                    ->options(TipoDocumento::All()->pluck('nombre','id')->toArray())
                    ->searchable()
                    ->preload()
                    ->live(),
                TextInput::make('cod_documento')
                    ->visible()
                    ->label('Código Identificativo'),
                Checkbox::make('con_plantilla')
                    ->label('¿Tiene plantilla?')
                    ->required()
                    ->reactive()
                    ->default(true),
                    //->afterStateUpdated(function ($state, callable $set){
                    //    $set('plantilla',  $state ? 'visible' : 'hidden');
                    //    $set('rutaPlantilla',  $state ? 'visible' : 'hidden');
                    //}),
                TextInput::make('plantilla')
                    ->visible(fn (Get $get): bool =>  $get('con_plantilla'))
                    ->label('Plantilla')
                    ->autocomplete(true),
                 TextInput::make('ruta_plantilla')
                    ->visible(fn (Get $get): bool =>  $get('con_plantilla'))
                    ->url ()
                    ->autocomplete(true),
                Checkbox::make('obligatorio')
                    ->required()
                    ->default(true),
                Select::make('cod_estado')
                    //->relationship(name : 'tipodoc', titleAttribute:'nombre')
                    ->label('Estado del expediente')
                    ->options(TablaDeEstados::All()->pluck('estado','cod_estado')->toArray())
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('cod_origen')
                    //->relationship(name : 'tipodoc', titleAttribute:'nombre')
                    ->label('Origen del documento')
                    ->options(DestinoDeDocumentos::All()->pluck('destino','id')->toArray())
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('cod_destino')
                    //->relationship(name : 'tipodoc', titleAttribute:'nombre')
                    ->label('Destinatario del documento')
                    ->options(DestinoDeDocumentos::All()->pluck('destino','id')->toArray())
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('entrada_salida')
                    ->label('Entrada/Salida')
                    ->options(['Entrada'=>'Entrada',
                                        'Salida'=>'Salida']),


            ])->columns(3);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                //
            TextColumn::make('nombre')
                ->sortable()
                ->searchable(),
            TextColumn::make('descripcion')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('tipodoc.nombre')
                ->sortable()
                ->searchable(),
            TextColumn::make('fasedoc.nombre')
                ->sortable()
                ->searchable(),
            TextColumn::make('fasedocsig.nombre')
                ->sortable()
                ->searchable(),
            TextColumn::make('destino.destino')


            ])
            ->filters([
                //
                //SelectFilter::make('tipo')
                //    ->relationship('tipoDocumento', 'tipodocumento')
                //    ->searchable()
                //    ->preload()
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
            'index' => ListDocumentoGenericos::route('/'),
            'create' => CreateDocumentoGenerico::route('/create'),
            'edit' => EditDocumentoGenerico::route('/{record}/edit'),
        ];
    }
}
