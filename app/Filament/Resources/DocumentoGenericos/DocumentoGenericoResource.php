<?php

namespace App\Filament\Resources\DocumentoGenericos;

use App\Filament\Resources\DocumentoGenericos\Pages\CreateDocumentoGenerico;
use App\Filament\Resources\DocumentoGenericos\Pages\EditDocumentoGenerico;
use App\Filament\Resources\DocumentoGenericos\Pages\ListDocumentoGenericos;
use App\Filament\Resources\DocumentoGenericos\RelationManagers\VariablesRelationManager;
use App\Models\DestinoDeDocumentos;
use App\Models\DocumentoGenerico;
use App\Models\FaseDocumento;
use App\Models\TablaDeEstados;
use App\Models\TipoDocumento;
use App\Services\Templates\WordTemplateVariableSynchronizer;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Resource Filament para el catálogo de documentos genéricos.
 *
 * Permite definir plantillas, fases, tipo documental, destinatarios y realizar
 * la sincronización automática de variables detectadas en archivos Word.
 */
class DocumentoGenericoResource extends Resource
{
    protected static ?string $model = DocumentoGenerico::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = 'Documentación';

    protected static ?string $navigationLabel = 'Documentos genéricos';

    /**
     * Define el formulario de mantenimiento del documento genérico y su plantilla.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre del documento')
                    ->required(),
                Textarea::make('descripcion')
                    ->required()
                    ->columnSpanFull(),
                Select::make('fase_doc')
                    ->options(FaseDocumento::all()->pluck('nombre', 'cod_fase')->toArray())
                    ->label('Fase')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('fase_siguiente')
                ->required()
                    ->options(FaseDocumento::all()->pluck('nombre', 'cod_fase')->toArray())
                    ->label('Fase siguiente')
                    ->searchable()
                    ->preload(),
                Select::make('cod_tipo_doc')
                    ->label('Tipo de documento')
                    ->required()
                    ->options(TipoDocumento::all()->pluck('nombre', 'id')->toArray())
                    ->searchable()
                    ->preload(),
                TextInput::make('cod_documento')
                    ->label('Código identificativo')
                    ->required(),
                Checkbox::make('con_plantilla')
                    ->label('¿Tiene plantilla?')
                    ->required()
                    ->default(true)
                    ->reactive(),
                TextInput::make('plantilla')
                    ->label('Nombre de plantilla')
                    ->visible(fn (Get $get): bool => (bool) $get('con_plantilla')),
                TextInput::make('ruta_plantilla')
                    ->label('Ruta de plantilla')
                    ->visible(fn (Get $get): bool => (bool) $get('con_plantilla'))
                    ->helperText('Ruta absoluta o relativa dentro de storage/app. Soporta .docx y .dotx.'),
                Checkbox::make('obligatorio')
                    ->label('Obligatorio')
                    ->required()
                    ->default(true),
                Select::make('cod_estado')
                    ->label('Estado del expediente')
                    ->options(TablaDeEstados::all()->pluck('estado', 'cod_estado')->toArray())
                    ->searchable()
                    ->preload(),
                Select::make('cod_origen')
                    ->label('Origen del documento')
                    ->required()
                    ->options(DestinoDeDocumentos::all()->pluck('destino', 'id')->toArray())
                    ->searchable()
                    ->preload(),
                Select::make('cod_destino')
                    ->label('Destinatario del documento')
                    ->required()
                    ->options(DestinoDeDocumentos::all()->pluck('destino', 'id')->toArray())
                    ->searchable()
                    ->preload(),
                Select::make('entrada_salida')
                    ->label('Entrada/Salida')
                    ->options([
                        'E' => 'Entrada',
                        'S' => 'Salida',
                        'C' => 'Coordinación',
                    ]),
            ])
            ->columns(3);
    }

    /**
     * Configura la tabla de catálogo con visibilidad de plantilla y número de variables detectadas.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipodoc.nombre')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fasedoc.nombre')
                    ->label('Fase')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fasedocsig.nombre')
                    ->label('Fase siguiente')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('con_plantilla')
                    ->label('Plantilla')
                    ->boolean(),
                TextColumn::make('variables_count')
                    ->label('Variables')
                    ->counts('variables'),
                TextColumn::make('destino.destino')
                    ->label('Destino'),
                TextColumn::make('ruta_plantilla')
                    ->label('Ruta plantilla')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('sincronizar_variables')
                    ->label('Sincronizar variables')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (DocumentoGenerico $record): void {
                        try {
                            $count = app(WordTemplateVariableSynchronizer::class)->sync($record);

                            Notification::make()
                                ->title('Variables sincronizadas')
                                ->body("Se han detectado {$count} variables en la plantilla.")
                                ->success()
                                ->send();
                        } catch (\Throwable $exception) {
                            Notification::make()
                                ->title('No se pudo sincronizar la plantilla')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Relación hija para editar y ajustar las variables de cada plantilla documental.
     */
    public static function getRelations(): array
    {
        return [
            VariablesRelationManager::class,
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

