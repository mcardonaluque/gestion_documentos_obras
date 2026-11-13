<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosCeroAprobacion\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Gestiona los documentos y CSV declarados en un Modelo 0 de aprobación.
 */
class DocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'documentos';

    protected static ?string $title = 'Documentos electrónicos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('csv')
                    ->label('Código seguro de verificación')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('contenido')
                    ->label('Contenido')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('denominacion')
                    ->label('Documento')
                    ->required()
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('orden')
            ->columns([
                TextColumn::make('orden')
                    ->label('Orden')
                    ->numeric(),
                TextColumn::make('contenido')
                    ->placeholder('Sin contenido')
                    ->limit(40)
                    ->wrap(),
                TextColumn::make('denominacion')
                    ->label('Documento')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('csv')
                    ->label('CSV')
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Añadir documento')
                    ->mutateDataUsing(function (array $data): array {
                        $nextOrder = ((int) $this->getOwnerRecord()
                            ->documentos()
                            ->max('orden')) + 1;

                        return [
                            ...$this->synchronizeDocumentData($data),
                            'orden' => $nextOrder,
                        ];
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateDataUsing(
                        fn (array $data): array => $this->synchronizeDocumentData($data),
                    ),
                DeleteAction::make(),
            ]);
    }

    /**
     * La plantilla distingue ambos marcadores, pero funcionalmente la
     * referencia y la denominación son el mismo dato declarado por el ayuntamiento.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function synchronizeDocumentData(array $data): array
    {
        return [
            ...$data,
            'referencia' => $data['denominacion'] ?? null,
        ];
    }
}
