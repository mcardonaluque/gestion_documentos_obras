<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\NormativaPpacs;

use App\Filament\Obras\Resources\NormativaPpacs\Pages\CreateNormativaPpac;
use App\Filament\Obras\Resources\NormativaPpacs\Pages\EditNormativaPpac;
use App\Filament\Obras\Resources\NormativaPpacs\Pages\ListNormativaPpacs;
use App\Models\NormativaPpac;
use App\Models\Planes;
use App\Filament\Support\AsignarNormativaAPlanesAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Validation\Rule;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NormativaPpacResource extends Resource
{
    protected static ?string $model = NormativaPpac::class;

    protected static ?string $navigationLabel = 'Normativa PPAC';

    protected static ?string $modelLabel = 'Normativa PPAC';

    protected static ?string $pluralModelLabel = 'Normativas PPAC';

    public static function yearValidationRules(): array
    {
        return ['required', 'integer', 'min:2000', 'max:2100'];
    }

    public static function aoPlanValidationRules(?int $ignoreId = null, ?string $codigoPlan = null): array
    {
        $rules = NormativaPpac::aoPlanValidationRules($ignoreId, $codigoPlan);

        return $rules;
    }

    protected static ?int $navigationSort = 5;

    protected static string | \UnitEnum | null $navigationGroup = 'Aprobacion de Obras';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
               Section::make('Fechas límites de presentación')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([  
                    Select::make('Codigo_Plan')
                        ->label('Plan (vacío = normativa general del año)')
                        ->options(fn (): array => Planes::query()
                            ->orderBy('codigo_plan')
                            ->get(['codigo_plan', 'denominacion_plan'])
                            ->mapWithKeys(fn (Planes $plan): array => [
                                trim((string) $plan->codigo_plan) => trim((string) $plan->codigo_plan) . ' - ' . trim((string) $plan->denominacion_plan),
                            ])
                            ->all())
                        ->searchable()
                        ->nullable()
                        ->live()
                        ->columnSpanFull(),
                    TextInput::make('ao_plan')
                        ->label('Año plan')
                        ->required()
                        ->rules(fn (?NormativaPpac $record, Get $get): array => self::aoPlanValidationRules($record?->id, $get('Codigo_Plan')))
                        ->validationMessages([
                            'unique' => 'Ya existe una normativa PPAC para ese año y plan.',
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
                        ->label('Fecha límite de cesión de la obra')
                        ->nullable()
                        ->default(fn () => now()->startOfDay()),
                    DateTimePicker::make('fecha_limite_contratacion')
                        ->label('Fecha límite de contratación')
                        ->nullable()
                        ->default(fn () => now()->startOfDay()),
                    DateTimePicker::make('fecha_limite_justificacion')
                                ->label('Fecha límite de justificación del plan')
                                ->required()
                                ->default(fn () => now()->startOfDay()),
                    TextInput::make('dias_prorroga_max_porcentaje')
                        ->label('% máximo de ampliación')
                        ->default(50)
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100),
                   ]),
                Section::make('Fechas límites de presentación - Redacción de proyecto Diputación')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        DateTimePicker::make('fecha_limite_presentacion_proyectoD')
                            ->label('Fecha límite de presentación del proyecto')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_limite_presentacion_documentacionD')
                            ->label('Fecha límite de presentación de la documentación')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_documentacionD_asiguiente')
                            ->label('Fecha documentación siguiente')
                            ->nullable(),
                        DateTimePicker::make('fecha_proyectoD_siguiente')
                            ->label('Fecha proyecto siguiente')
                            ->nullable(),

                    ]),
                Section::make('Fechas límites de presentación  - Redacción de proyecto Ayuntamientos')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        DateTimePicker::make('fecha_limite_presentacion_proyectoA')
                            ->label('Fecha límite de presentación del proyecto')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_limite_presentacion_documentacionA')
                            ->label('Fecha límite de presentación de la documentación')
                            ->required()
                            ->default(fn () => now()->startOfDay()),
                        DateTimePicker::make('fecha_documentacionA_asiguiente')
                            ->label('Fecha documentación siguiente')
                            ->nullable(),
                        DateTimePicker::make('fecha_proyectoA_siguiente')
                            ->label('Fecha proyecto siguiente')
                            ->nullable(),

                    ]),


                Textarea::make('observaciones')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('ao_plan', 'desc')
            ->columns([
                TextColumn::make('ao_plan')->label('Año plan')->sortable()->searchable(),
                TextColumn::make('Codigo_Plan')->label('Plan')->placeholder('General')->sortable()->searchable(),
                TextColumn::make('ao_fin_plan')->label('Año fin')->sortable(),
                TextColumn::make('fecha_aprobacion_plan')->label('Aprobación')->date()->sortable(),
                TextColumn::make('fecha_publicacion_definitiva')->label('Publicación')->date()->sortable(),
                TextColumn::make('fecha_limite_terminacion_plan')->label('Límite Ejecución')->date()->sortable(),
                TextColumn::make('fecha_limite_justificacion')->label('Límite justificación')->date()->sortable(),
                TextColumn::make('fecha_limite_cesion_obra')->label('Límite cesión obra')->date(),
                TextColumn::make('fecha_limite_contratacion')->label('Límite contratación')->date(),
                TextColumn::make('fecha_documentacionD_asiguiente')->label('Doc. D sig.')->date(),
                TextColumn::make('fecha_documentacionA_asiguiente')->label('Doc. A sig.')->date(),
                TextColumn::make('fecha_proyectoD_siguiente')->label('Proj. D sig.')->date(),
                TextColumn::make('fecha_proyectoA_siguiente')->label('Proj. A sig.')->date(),
            ])
            ->recordActions([
                AsignarNormativaAPlanesAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNormativaPpacs::route('/'),
            'create' => CreateNormativaPpac::route('/create'),
            'edit' => EditNormativaPpac::route('/{record}/edit'),
        ];
    }
}
