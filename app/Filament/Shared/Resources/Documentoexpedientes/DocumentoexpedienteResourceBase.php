<?php

namespace App\Filament\Shared\Resources\Documentoexpedientes;

use App\Models\DocumentoExpediente;
use App\Models\Planes;
use Filament\Facades\Filament;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Livewire;
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
                Select::make('expediente_id')
                    ->label('Expediente')
                    ->relationship('expedientes', 'expediente_id')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                        $defaults = DocumentoExpediente::applyExpedienteDefaults([
                            'expediente_id' => $state,
                            'team_id' => $get('team_id'),
                        ]);

                        $set('Codigo_Plan', $defaults['Codigo_Plan'] ?? null);
                        $set('referencia', $defaults['referencia'] ?? null);
                        $set('subreferencia', $defaults['subreferencia'] ?? null);
                        $set('ao_ejecucion', $defaults['ao_ejecucion'] ?? null);
                        $set('nsecuencia', $defaults['nsecuencia'] ?? null);

                        $planNombre = null;
                        if (! blank($defaults['Codigo_Plan'] ?? null)) {
                            $planNombre = Planes::query()
                                ->where('codigo_plan', $defaults['Codigo_Plan'])
                                ->value('denominacion_plan');
                        }
                        $set('plan_nombre', $planNombre);

                        if (! blank($defaults['team_id'] ?? null)) {
                            $set('team_id', $defaults['team_id']);
                        }
                    })
                    ->afterStateHydrated(function ($state, callable $set, callable $get): void {
                        if (blank($state)) {
                            return;
                        }

                        $defaults = DocumentoExpediente::applyExpedienteDefaults([
                            'expediente_id' => $state,
                            'team_id' => $get('team_id'),
                            'Codigo_Plan' => $get('Codigo_Plan'),
                        ]);

                        $set('Codigo_Plan', $defaults['Codigo_Plan'] ?? $get('Codigo_Plan'));

                        $planNombre = null;
                        if (! blank($defaults['Codigo_Plan'] ?? null)) {
                            $planNombre = Planes::query()
                                ->where('codigo_plan', $defaults['Codigo_Plan'])
                                ->value('denominacion_plan');
                        }

                        $set('plan_nombre', $planNombre);
                    })
                    ->required(),
                TextInput::make('Codigo_Plan')
                    ->label('Código Plan')
                    ->readOnly()
                    ->required(),
                TextInput::make('plan_nombre')
                    ->label('Plan')
                    ->readOnly()
                    ->dehydrated(false)
                    ->default(fn ($record) => $record?->planes?->denominacion_plan),
                TextInput::make('referencia')
                    ->label('Número obra')
                    ->readOnly()
                    ->required()
                    ->numeric(),
                TextInput::make('subreferencia')
                    ->label('Subreferencia')
                    ->readOnly()
                    ->numeric()
                    ->default(null),
                TextInput::make('ao_ejecucion')
                    ->label('Año ejecución')
                    ->readOnly()
                    ->required()
                    ->numeric(),
                DatePicker::make('fechaincorporacion')
                    ->default(now())
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $livewire): void {
                        if (! method_exists($livewire, 'validateDateFieldOnBlur')) {
                            return;
                        }

                        $livewire->validateDateFieldOnBlur('fechaincorporacion');
                    })
                    ->required(),
                DatePicker::make('fechaHelp'),
                Select::make('cod_documento')
                    ->relationship('tipodocumentos', 'nombre')
                    ->required(),
                TextInput::make('archivo')
                    ->label('Ruta o URL del PDF')
                    ->placeholder('X:\\docs\\expediente_ie\\nombrearchivo.pdf o https://...')
                    ->maxLength(1000)
                    ->default(null),
                TextInput::make('csv')
                    ->required()
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('nregistro')
                    ->maxLength(45)
                    ->default(null),
                TextInput::make('nsecuencia')
                    ->readOnly()
                    ->numeric()
                    ->default(null),
                Select::make('estado')
                    ->relationship('estados', 'nombre')
                    ->required(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
                Select::make('team_id')
                    ->label('Municipio')
                    ->relationship('team', 'name')
                    ->default(fn () => Filament::getTenant()?->id),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Codigo_Plan')
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
                TextColumn::make('tipodocumentos.nombre')
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
                TextColumn::make('estados.nombre')
                    ->label('Estado')
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->label('Municipio')
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
