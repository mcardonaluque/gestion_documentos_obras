<?php

namespace App\Filament\Shared\Resources\Expedientes\RelationManagers;

use App\Models\DocumentoExpediente;
use App\Models\DocumentoGenerico;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Livewire;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'documentos';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Codigo_Plan')
                    ->label('Código plan')
                    ->readOnly()
                    ->required(),
                TextInput::make('referencia')
                    ->label('Número obra')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->referencia)
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->subreferencia)
                    ->numeric(),
                TextInput::make('ao_ejecucion')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->ao_ejecucion)
                    ->required()
                    ->numeric(),
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->readOnly()
                    ->default(fn () => $this->getOwnerRecord()?->expediente_id)
                    ->required(),
                DatePicker::make('fechaincorporacion')
                    ->default(now())
                    ->required(),
                DatePicker::make('fechaHelp'),
                Select::make('cod_documento')
                    ->relationship('tipodocumentos', 'nombre')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $documentType = DocumentoGenerico::query()->find($state);

                        $set('destino', $documentType?->cod_destino ?: null);
                        $set('procedencia', $documentType?->cod_origen ?: null);
                    })
                    ->required(),
                TextInput::make('archivo')
                    ->label('Ruta o URL del PDF')
                    ->placeholder('X:\\docs\\expediente_ie\\nombrearchivo.pdf o https://...')
                    ->maxLength(1000)
                    ->default(null),
                TextInput::make('csv')
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                TextInput::make('nsecuencia')
                    ->readOnly()
                    ->default(fn () => ((int) ($this->getOwnerRecord()?->documentos()?->max('nsecuencia') ?? 0)) + 1)
                    ->numeric(),
                Select::make('estado')
                    ->relationship('estados', 'nombre')
                    ->required(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
               Select::make('destino')
                    ->relationship('destinos', 'destino')
                    ->label('Destino'),
                Select::make('procedencia')
                    ->relationship('procedencias', 'destino')
                    ->label('Procedencia'),
                Livewire::make(\App\Filament\Widgets\DocumentoPdfViewerWidget::class, [
                    'compact' => true,
                ])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('Codigo_Plan')
                    ->label('Código plan')
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
                TextColumn::make('cod_documento')
                    ->label('Documento')
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->fillForm(function (): array {
                        $ownerRecord = $this->getOwnerRecord();

                        return [
                            'Codigo_Plan' => $ownerRecord?->Codigo_Plan,
                            'referencia' => $ownerRecord?->referencia,
                            'subreferencia' => $ownerRecord?->subreferencia,
                            'ao_ejecucion' => $ownerRecord?->ao_ejecucion,
                            'expediente_id' => $ownerRecord?->expediente_id,
                            'nsecuencia' => ((int) ($ownerRecord?->documentos()?->max('nsecuencia') ?? 0)) + 1,
                            'fechaincorporacion' => now(),
                        ];
                    })
                    ->mutateDataUsing(function (array $data): array {
                        $ownerRecord = $this->getOwnerRecord();
                        $data['expediente_id'] = $ownerRecord?->expediente_id;

                        return DocumentoExpediente::applyExpedienteDefaults($data);
                    }),
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
