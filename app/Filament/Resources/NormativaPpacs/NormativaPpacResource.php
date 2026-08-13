<?php

declare(strict_types=1);

namespace App\Filament\Resources\NormativaPpacs;

use App\Filament\Resources\NormativaPpacs\Pages;
use App\Models\NormativaPpac;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Illuminate\Validation\Rule;

class NormativaPpacResource extends Resource
{
    protected static ?string $model = NormativaPpac::class;

    protected static bool $isScopedToTenant = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = 'Administracion de prorrogas';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Normativa PPAC';

    protected static ?string $modelLabel = 'Normativa PPAC';

    protected static ?string $pluralModelLabel = 'Normativas PPAC';

    public static function yearValidationRules(): array
    {
        return ['required', 'integer', 'min:2000', 'max:2100'];
    }

    public static function aoPlanValidationRules(?int $ignoreId = null): array
    {
        $rules = self::yearValidationRules();
        $uniqueRule = Rule::unique('NormativaPPAC', 'ao_plan');

        if ($ignoreId !== null) {
            $uniqueRule = $uniqueRule->ignore($ignoreId);
        }

        $rules[] = $uniqueRule;

        return $rules;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('ao_plan')
                    ->label('Año plan')
                    ->required()
                    ->rules(fn (?NormativaPpac $record): array => self::aoPlanValidationRules($record?->id))
                    ->validationMessages([
                        'unique' => 'Ya existe una normativa PPAC para ese año de plan.',
                    ])
                    ->numeric()
                    ->step(1)
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->live(onBlur: true)
                    ->afterStateHydrated(function (mixed $state, callable $set): void {
                        if (! is_numeric($state)) {
                            return;
                        }

                        $set('ao_fin_plan', NormativaPpac::calculatePlanEndYear((int) $state));
                    })
                    ->afterStateUpdated(function (mixed $state, callable $set): void {
                        if (! is_numeric($state)) {
                            return;
                        }

                        $set('ao_fin_plan', NormativaPpac::calculatePlanEndYear((int) $state));
                    }),
                TextInput::make('ao_fin_plan')
                    ->label('Año fin plan')
                    ->required()
                    ->rules(self::yearValidationRules())
                    ->numeric()
                    ->step(1)
                    ->minValue(2000)
                    ->maxValue(2100),
                DateTimePicker::make('fecha_aprobacion_plan')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_publicacion_definitiva')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateHydrated(function (mixed $state, callable $set): void {
                        if ($state === null || $state === '') {
                            return;
                        }

                        $documentationDate = NormativaPpac::calculateDocumentationDeadline($state);
                        $projectDate = NormativaPpac::calculateProjectDeadline($state);
                        $set('fecha_limite_presentacion_documentacionD', $documentationDate);
                        $set('fecha_limite_presentacion_documentacionA', $documentationDate);
                        $set('fecha_proyectoD_siguiente', $projectDate);
                        $set('fecha_proyectoA_siguiente', $projectDate);
                    })
                    ->afterStateUpdated(function (mixed $state, callable $set): void {
                        if ($state === null || $state === '') {
                            return;
                        }

                        $documentationDate = NormativaPpac::calculateDocumentationDeadline($state);
                        $projectDate = NormativaPpac::calculateProjectDeadline($state);
                        $set('fecha_limite_presentacion_documentacionD', $documentationDate);
                        $set('fecha_limite_presentacion_documentacionA', $documentationDate);
                        $set('fecha_proyectoD_siguiente', $projectDate);
                        $set('fecha_proyectoA_siguiente', $projectDate);
                    })
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_terminacion_plan')
                    ->label('Fecha límite de ejecución de obras')
                    ->required()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_cesion_obra')
                    ->label('Fecha limite de cesión de la obra')
                    ->nullable()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_contratacion')
                    ->label('Fecha limite de contratación')
                    ->nullable()
                    ->default(fn () => now()->startOfDay()),
                DateTimePicker::make('fecha_limite_justificacion')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                TextInput::make('dias_prorroga_max_porcentaje')
                    ->label('% maximo de ampliacion')
                    ->default(50)
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100),
                Section::make('Fechas limites de presentacion - Redacción de proyecto Diputación')
                    ->columns(2)
                    ->schema([

                        DateTimePicker::make('fecha_limite_presentacion_proyectoD')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_limite_presentacion_documentacionD')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_documentacionD_asiguiente')
                            ->label('Fecha documentación Anualidad siguiente')
                            ->nullable(),
                        DateTimePicker::make('fecha_proyectoD_siguiente')
                            ->label('Fecha proyecto siguiente')
                            ->nullable(),

                    ]),

                Section::make('Fechas limites de presentacion - Redacción de proyecto Diputación')
                    ->columns(2)
                    ->schema([

                        DateTimePicker::make('fecha_limite_presentacion_proyectoA')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_limite_presentacion_documentacionA')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_documentacionA_asiguiente')
                            ->label('Fecha documentación Anualidad siguiente')
                            ->nullable(),
                        DateTimePicker::make('fecha_proyectoA_siguiente')
                            ->label('Fecha proyecto siguiente')
                            ->nullable(),

                    ]),


                Textarea::make('observaciones')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('ao_plan', 'desc')
            ->columns([
                TextColumn::make('ao_plan')
                    ->label('Año plan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ao_fin_plan')
                    ->label('Año fin')
                    ->sortable(),
                TextColumn::make('fecha_aprobacion_plan')
                    ->label('Aprobación')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_publicacion_definitiva')
                    ->label('Publicacion')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('fecha_limite_terminacion_plan')
                    ->label('Límite Ejecución')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_limite_justificacion')
                    ->label('Limite justificacion')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('fecha_limite_cesion_obra')
                    ->label('Límite cesión obra')
                    ->dateTime(),
                TextColumn::make('fecha_limite_contratacion')
                    ->label('Límite contratación')
                    ->dateTime(),
                TextColumn::make('fecha_documentacionD_asiguiente')
                    ->label('Doc. D sig.')
                    ->dateTime(),
                TextColumn::make('fecha_documentacionA_asiguiente')
                    ->label('Doc. A sig.')
                    ->dateTime(),
                TextColumn::make('fecha_proyectoD_siguiente')
                    ->label('Proj. D sig.')
                    ->dateTime(),
                TextColumn::make('fecha_proyectoA_siguiente')
                    ->label('Proj. A sig.')
                    ->dateTime(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNormativaPpacs::route('/'),
            'create' => Pages\CreateNormativaPpac::route('/create'),
            'edit' => Pages\EditNormativaPpac::route('/{record}/edit'),
        ];
    }
}
