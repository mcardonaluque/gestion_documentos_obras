<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\RelationManagers\DocumentoExpedienteRelationManager;
use App\Filament\Obras\Resources\ExpedienteResource\Pages;
use App\Filament\Obras\Resources\ExpedienteResource\RelationManagers;
use App\Models\Expediente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpedienteResource extends Resource
{
    protected static ?string $model = Expediente::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(5)
            ->schema([
                Forms\Components\TextInput::make('expediente_id')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('codigo_plan')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subreferencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nombre_obra')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('cod_estado')
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('cod_estado_help')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('forma_ejecucion')
                    ->maxLength(6),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        
            ->columns([
                //Tables\Columns\Layout\View::make('expandible-document-table')
                //    ->visible(fn ($record) => $record->documentos->isNotEmpty()),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('expediente_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_plan')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_obra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estados.estado_abrev')
                    ->sortable()
                    ->searchable()
                    ->grow()
                    ->extraHeaderAttributes(['class' => 'px-8'])
                    ->extraCellAttributes(['class' => 'px-8'])
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('cod_estado_help')
                    ->searchable(),
                Tables\Columns\TextColumn::make('forma_ejecucion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
               
                Tables\Columns\ViewColumn::make('documentos')
                    ->label('docs')
                    //->view('documentos-inline')
                    ->view('expediente-documentos')
                   
            ])
                      
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                             
               // Acción para subir documento
                    Tables\Actions\Action::make('subirDocumento')
                        ->label('Subir Documento')
                        ->icon('heroicon-o-paper-clip')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('descripcion')
                                ->label('Descripción del Documento')
                                ->required()
                                ->maxLength(255),
                            
                            Forms\Components\Select::make('cod_documento')
                                ->label('Tipo de Documento')
                                ->options(
                                    \App\Models\DocumentoGenerico::query()
                                        ->orderBy('nombre')
                                        ->pluck('nombre', 'id')
                                )
                                ->searchable()
                                ->required(),
                            
                            Forms\Components\DatePicker::make('created_at')
                                ->label('Fecha de Incorporación')
                                ->required()
                                ->default(now()),
                            
                            Forms\Components\FileUpload::make('archivo')
                                ->label('Archivo PDF')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(10240) // 10MB
                                ->directory('documentos-expedientes')
                                ->preserveFilenames()
                                ->required(),
                            
                        ])
                        ->action(function (array $data, $record) {
                            // Aquí defines qué hacer con los datos del formulario
                            // Por ejemplo, crear el documento relacionado:
                            $record->documentos()->create([
                                'descripcion' => $data['descripcion'],
                                'cod_documento' => $data['cod_documento'],
                                'created_at' => $data['created_at'],
                                'csv' => $data['archivo'],
                                'estado' => 'Nuevo',
                            ]);
                            
                            Notification::make()
                                ->title('Documento subido correctamente')
                                ->success()
                                ->send();
                        }),    
                     Tables\Actions\Action::make('ver_documentos')
                        ->label('Documentos')
                        ->icon('heroicon-o-folder-open')
                        ->slideOver() // ->modal() si prefieres modal
                        ->modalWidth('xl') // opcional: ancho del modal
                        ->modalHeading(fn ($record) => "Documentos del expediente {$record->expediente_id}")
                        ->modalContent(function($record){
                                                       
                            // Verificar datos específicos
                            foreach ($record->documentos as $index => $doc) {
                                logger("Documento {$index}: ID={$doc->id}, Descripción={$doc->descripcion}");
                            }
                           
                            return view('documentos-slideover', [
                            'expediente' => $record,
                            'documentos' => $record->documentos]);//->loadMissing(['tipodocumentos','team']); // eager load si quieres
                        
                    }),   
                    
            ])
            //->query(fn (Builder $query) => $query->with('documentos'))
            ->recordUrl(null) // ❌ Desactiva la acción por defecto "editar" al hacer clic
            ->defaultSort('expediente_id', 'asc')
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount('documentos')->with('documentos'))
        // O redirigir correctamente
            //->recordAction('ver_documentos') // Esto haría que el click en la fila abra tu acción
           
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
            DocumentoExpedienteRelationManager::class

        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('documentos');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpedientes::route('/'),
            'create' => Pages\CreateExpediente::route('/create'),
            'edit' => Pages\EditExpediente::route('/{record}/edit'),
        ];
    }
}
