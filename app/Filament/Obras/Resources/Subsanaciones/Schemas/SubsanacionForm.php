<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Schemas;

use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Carbon;

/**
 * Schema reutilizable del formulario de subsanaciones.
 *
 * Centraliza los componentes para que el recurso independiente
 * (SubsanacionResource) y el relation manager del expediente
 * (SubsanacionesRelationManager) compartan la misma definición,
 * variando solo el origen del expediente.
 */
final class SubsanacionForm
{
    /**
     * Componentes del formulario.
     *
     * @param  ?string  $expedienteId  Expediente fijo (relation manager); null para seleccionarlo en el formulario.
     * @return array<int, \Filament\Forms\Components\Field>
     */
    public static function components(?string $expedienteId = null): array
    {
        $components = [];

        if ($expedienteId === null) {
            $components[] = Select::make('expediente_id')
                ->label('Expediente')
                ->required()
                ->searchable()
                ->getSearchResultsUsing(fn (string $search): array => self::expedienteSearch($search))
                ->live()
                ->afterStateUpdated(function (callable $set): void {
                    $set('documento_id', null);
                    $set('documento_subsanacion_id', null);
                })
                ->columnSpanFull();
        }

        return [
            ...$components,
            Select::make('documento_id')
                ->label('Documento a subsanar')
                ->required()
                ->searchable()
                ->preload()
                ->options(fn (callable $get): array => self::documentoOptions($expedienteId ?? $get('expediente_id'))),
            Select::make('documento_subsanacion_id')
                ->label('Documento de subsanación')
                ->helperText('Documento aportado que resuelve la subsanación.')
                ->searchable()
                ->preload()
                ->options(fn (callable $get): array => self::documentoOptions($expedienteId ?? $get('expediente_id'))),
            Textarea::make('motivo_subsanacion')
                ->label('Motivo de la subsanación')
                ->required()
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('plazo_subsanacion')
                ->label('Plazo (días)')
                ->numeric()
                ->minValue(1)
                ->maxValue(365),
            DatePicker::make('fecha_solicitud_subsanacion')
                ->label('Fecha de solicitud')
                ->required()
                ->default(now()),
            DatePicker::make('fecha_subsanacion')
                ->label('Fecha de subsanación')
                ->afterOrEqual('fecha_solicitud_subsanacion'),
        ];
    }

    /**
     * Opciones de documentos de un expediente (id => descripción y fecha).
     *
     * @return array<string, string>
     */
    public static function documentoOptions(?string $expedienteId): array
    {
        if ($expedienteId === null || trim($expedienteId) === '') {
            return [];
        }

        return DocumentoExpediente::query()
            ->where('expediente_id', $expedienteId)
            ->orderByDesc('fechaincorporacion')
            ->limit(200)
            ->get(['idDocumento', 'descripcion', 'fechaincorporacion'])
            ->mapWithKeys(function (DocumentoExpediente $documento): array {
                $fecha = filled($documento->fechaincorporacion)
                    ? ' (' . Carbon::parse((string) $documento->fechaincorporacion)->format('d/m/Y') . ')'
                    : '';

                return [(string) $documento->idDocumento => trim((string) $documento->descripcion) . $fecha];
            })
            ->all();
    }

    /**
     * Búsqueda de expedientes por identificador para el select del recurso.
     *
     * @return array<string, string>
     */
    public static function expedienteSearch(string $search): array
    {
        return Expediente::query()
            ->where('expediente_id', 'like', '%' . $search . '%')
            ->orderByDesc('ao_ejecucion')
            ->limit(50)
            ->pluck('expediente_id', 'expediente_id')
            ->all();
    }
}
