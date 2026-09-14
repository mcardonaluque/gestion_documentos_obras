<?php

declare(strict_types=1);

namespace App\Filament\Resources\TramitadorApiOperaciones\RelationManagers;

use App\Models\TramitadorApiParametro;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class ParametrosRelationManager extends RelationManager
{
    protected static string $relationship = 'parametros';

    protected static ?string $title = 'Parámetros';

    private const PRIORIDAD_UBICACION_ENTRADA = [
        'path' => 1,
        'query' => 2,
        'header' => 3,
        'body' => 4,
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('direccion')
                    ->label('Dirección')
                    ->options([
                        'entrada' => 'Entrada',
                        'salida' => 'Salida',
                    ])
                    ->required(),
                Select::make('ubicacion')
                    ->label('Ubicación')
                    ->options([
                        'path' => 'Ruta',
                        'query' => 'Consulta',
                        'body' => 'Cuerpo',
                        'header' => 'Cabecera',
                        'response' => 'Respuesta',
                    ])
                    ->required(),
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(160),
                TextInput::make('etiqueta')
                    ->label('Etiqueta')
                    ->maxLength(160),
                Select::make('tipo_dato')
                    ->label('Tipo de dato')
                    ->options([
                        'string' => 'Texto',
                        'integer' => 'Entero',
                        'number' => 'Número',
                        'boolean' => 'Booleano',
                        'date' => 'Fecha',
                        'datetime' => 'Fecha y hora',
                        'array' => 'Lista',
                        'object' => 'Objeto',
                        'file' => 'Archivo',
                    ])
                    ->required()
                    ->default('string'),
                TextInput::make('formato')
                    ->label('Formato')
                    ->helperText('Ejemplo: Y-m-d')
                    ->maxLength(100),
                Toggle::make('obligatorio')
                    ->label('Obligatorio')
                    ->default(false),
                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
                TextInput::make('orden')
                    ->label('Orden')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                TextInput::make('valor_por_defecto')
                    ->label('Valor por defecto')
                    ->maxLength(1000)
                    ->columnSpan(2),
                TextInput::make('ruta_json')
                    ->label('Ruta JSON')
                    ->helperText('Solo salidas; relativa a data, ejemplo: expediente.id')
                    ->maxLength(500)
                    ->columnSpan(2),
                Textarea::make('reglas_validacion')
                    ->label('Reglas de validación')
                    ->helperText('Reglas de Laravel separadas por |')
                    ->columnSpanFull(),
                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->orderBy('direccion')
                ->orderBy('orden'))
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->badge(),
                TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->badge(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('tipo_dato')
                    ->label('Tipo')
                    ->badge(),
                 TextColumn::make('ruta_json')
                    ->label('Ruta JSON')
                    ->badge(),
                IconColumn::make('obligatorio')
                    ->label('Obligatorio')
                    ->boolean(),
                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        // El orden es independiente para entradas y salidas.
                        $parametros = TramitadorApiParametro::query()
                            ->where('operacion_id', $this->getOwnerRecord()->getKey())
                            ->where('direccion', $data['direccion']);

                        if ($data['direccion'] !== 'entrada') {
                            $data['orden'] = (int) $parametros->max('orden') + 1;

                            return $data;
                        }

                        $prioridad = self::PRIORIDAD_UBICACION_ENTRADA[$data['ubicacion']] ?? PHP_INT_MAX;
                        // Inserta las rutas antes de query, header y body.
                        $orden = (int) (clone $parametros)
                            ->whereRaw(
                                'CASE ubicacion WHEN ? THEN 1 WHEN ? THEN 2 WHEN ? THEN 3 WHEN ? THEN 4 ELSE 5 END <= ?',
                                ['path', 'query', 'header', 'body', $prioridad],
                            )
                            ->max('orden') + 1;

                        $parametros
                            ->where('orden', '>=', $orden)
                            ->whereRaw(
                                'CASE ubicacion WHEN ? THEN 1 WHEN ? THEN 2 WHEN ? THEN 3 WHEN ? THEN 4 ELSE 5 END > ?',
                                ['path', 'query', 'header', 'body', $prioridad],
                            )
                            ->increment('orden');

                        $data['orden'] = $orden;

                        return $data;
                    })
                    ->before(function (array $data): void {
                        $exists = TramitadorApiParametro::query()
                            ->where('operacion_id', $this->getOwnerRecord()->getKey())
                            ->where('direccion', $data['direccion'])
                            ->where('ubicacion', $data['ubicacion'])
                            ->where('nombre', $data['nombre'])
                            ->exists();

                        if ($exists) {
                            throw ValidationException::withMessages([
                                'data.nombre' => 'Ya existe un parámetro con esta dirección, ubicación y nombre para esta función API.',
                            ]);
                        }
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
