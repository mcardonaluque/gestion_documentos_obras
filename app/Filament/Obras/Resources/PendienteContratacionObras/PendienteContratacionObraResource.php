<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\PendienteContratacionObras;

use App\Enums\EstadoContratacionObra;
use App\Filament\Obras\Resources\PendienteContratacionObras\Pages\CreatePendienteContratacionObra;
use App\Filament\Obras\Resources\PendienteContratacionObras\Pages\EditPendienteContratacionObra;
use App\Filament\Obras\Resources\PendienteContratacionObras\Pages\ListPendienteContratacionObras;
use App\Models\Contratista;
use App\Models\PendienteContratacionObra;
use App\Services\PendienteContratacionObraQueryService;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class PendienteContratacionObraResource extends Resource
{
    protected static ?string $model = PendienteContratacionObra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentMagnifyingGlass;

    protected static ?string $navigationLabel = 'Pendientes de contratación';

    protected static ?string $modelLabel = 'obra pendiente de contratación';

    protected static ?string $pluralModelLabel = 'Pendientes de contratación';

    protected static ?string $recordTitleAttribute = 'expediente_id';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('contratista');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextInput::make('expediente_id')->label('Expediente')->required()->maxLength(255),
                TextInput::make('PlanObra')->label('Plan de obra')->required()->maxLength(20),
                TextInput::make('NumObra')->label('Núm. obra')->required()->numeric(),
                TextInput::make('SubRef')->label('Subref.')->required()->numeric(),
                TextInput::make('AoObra')->label('Año ejecución')->required()->numeric(),
                Select::make('CodContratista')
                    ->label('Contratista')
                    ->options(fn (): array => Contratista::query()
                        ->orderBy('Nombre')
                        ->get(['Codigo_contratista', 'Nombre', 'Empresa'])
                        ->mapWithKeys(fn (Contratista $contratista): array => [
                            (string) $contratista->Codigo_contratista => trim((string) ($contratista->Nombre ?: $contratista->Empresa)),
                        ])
                        ->all())
                    ->searchable()
                    ->native(false),
                Select::make('CodClaseexpediente')
                    ->label('Clase de expediente')
                    ->options(fn (): array => app('db')->connection('Obras')
                        ->table('TbClasesExpedientes')
                        ->orderBy('claseexped')
                        ->pluck('claseexped', 'codclaseexped')
                        ->mapWithKeys(fn (string $name, mixed $code): array => [(string) $code => trim($name)])
                        ->all())
                    ->searchable()
                    ->native(false),
                Select::make('Codprocedimiento')
                    ->label('Procedimiento')
                    ->options(fn (): array => app('db')->connection('Obras')
                        ->table('TbTiposProcedimientos')
                        ->orderBy('procedimiento')
                        ->pluck('procedimiento', 'codprocedimiento')
                        ->mapWithKeys(fn (string $name, mixed $code): array => [(string) $code => trim($name)])
                        ->all())
                    ->searchable()
                    ->native(false),
                Select::make('CodFormaContrata')
                    ->label('Forma de contratación')
                    ->options(fn (): array => app('db')->connection('Obras')
                        ->table('TbFormasContratacion')
                        ->orderBy('FormaContratacion')
                        ->pluck('FormaContratacion', 'CodFormaContra')
                        ->mapWithKeys(fn (string $name, mixed $code): array => [(string) $code => trim($name)])
                        ->all())
                    ->searchable()
                    ->native(false),
                DateTimePicker::make('FechaAdjudicacion')->label('Fecha adjudicación'),
                DateTimePicker::make('FechaContrato')->label('Fecha contrato'),
                TextInput::make('ImporteAdjudicacion')->label('Importe adjudicación')->numeric(),
                TextInput::make('ImporteAdjudicacion_Pts')->label('Importe adjudicación (pts.)')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expediente_id')->label('Expediente')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('PlanObra')->label('Plan de obra')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('NumObra')->label('Núm. obra')->sortable(),
                Tables\Columns\TextColumn::make('SubRef')->label('Subref.')->sortable(),
                Tables\Columns\TextColumn::make('AoObra')->label('Año ejecución')->sortable(),
                Tables\Columns\TextColumn::make('CodContratista')->label('Cód. contratista')->placeholder('-'),
                Tables\Columns\TextColumn::make('contratista.Nombre')->label('Contratista')->placeholder('-'),
                Tables\Columns\TextColumn::make('FechaAdjudicacion')->label('Fecha adjudicación')->date()->placeholder('-'),
                Tables\Columns\TextColumn::make('ImporteAdjudicacion')->label('Importe adjudicación')->numeric(decimalPlaces: 2)->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\Filter::make('busqueda_obra')
                    ->label('Datos de obra')
                    ->form([
                        TextInput::make('expediente_id')->label('Expediente'),
                        TextInput::make('plan_obra')->label('Plan de obra'),
                        TextInput::make('num_obra')->label('Núm. obra')->numeric(),
                        TextInput::make('subref')->label('Subref.')->numeric(),
                        TextInput::make('ao_ejecucion')->label('Año ejecución')->numeric(),
                    ])
                    ->query(static function (Builder $query, array $data): Builder {
                        /** @var array{expediente_id?: string|null, plan_obra?: string|null, num_obra?: string|null, subref?: string|null, ao_ejecucion?: string|null} $data */
                        return app(PendienteContratacionObraQueryService::class)->applySearchFilters($query, $data);
                    }),
                Tables\Filters\SelectFilter::make('estado_contratacion')
                    ->label('Estado de contratación')
                    ->options([
                        EstadoContratacionObra::Contratada->value => EstadoContratacionObra::Contratada->label(),
                        EstadoContratacionObra::Pendiente->value => EstadoContratacionObra::Pendiente->label(),
                    ])
                    ->query(static function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        $status = is_string($value) ? EstadoContratacionObra::tryFrom($value) : null;

                        return $status instanceof EstadoContratacionObra
                            ? app(PendienteContratacionObraQueryService::class)->applyContractingStatus($query, $status)
                            : $query;
                    }),
            ])
            ->recordActions([
                EditAction::make()->label('Editar contratación'),
            ])
            ->headerActions([
                CreateAction::make()->label('Añadir contratación'),
            ])
            ->defaultSort('AoObra', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPendienteContratacionObras::route('/'),
            'create' => CreatePendienteContratacionObra::route('/create'),
            'edit' => EditPendienteContratacionObra::route('/{record}/edit'),
        ];
    }
}
