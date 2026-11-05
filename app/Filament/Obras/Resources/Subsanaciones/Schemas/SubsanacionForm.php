<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Schemas;

use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use App\Models\SubsanacionFirmante;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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
     * @return array<int, Field>
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
                ->default(request()->query('documento_id'))
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
            FileUpload::make('archivo_solicitud')
                ->label('Documento de solicitud')
                ->helperText('PDF que se enviará a la cadena de validación y firma.')
                ->disk('local')
                ->directory('subsanaciones/solicitudes')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(10240)
                ->preserveFilenames()
                ->required()
                ->columnSpanFull(),
            TextInput::make('tipo_documental_solicitud')
                ->label('Tipo documental NIMBUS')
                ->default('TD01')
                ->required()
                ->maxLength(50),
            Repeater::make('firmantes')
                ->label('Cadena de validación y firma')
                ->relationship()
                ->schema([
                    Select::make('user_id')
                        ->label('Usuario')
                        ->options(fn (): array => User::query()
                            ->role(['Jefe Del Servicio', 'Abogado', 'TAC', 'Diputado'])
                            ->whereNotNull('id_usuario')
                            ->where('id_usuario', '<>', '')
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all())
                        ->searchable()
                        ->required(),
                    Select::make('id_rol')
                        ->label('Rol NIMBUS')
                        ->options([
                            'Jefe Del Servicio' => 'Jefe Del Servicio',
                            'Abogado' => 'Abogado',
                            'TAC' => 'TAC',
                            'Diputado' => 'Diputado',
                        ])
                        ->required(),
                    Select::make('tipo')
                        ->label('Acción')
                        ->options([
                            SubsanacionFirmante::TIPO_VALIDACION => 'Valida',
                            SubsanacionFirmante::TIPO_FIRMA => 'Firma',
                        ])
                        ->default(SubsanacionFirmante::TIPO_FIRMA)
                        ->required(),
                ])
                ->orderColumn('orden')
                ->defaultItems(1)
                ->addActionLabel('Añadir integrante')
                ->reorderableWithButtons()
                ->required()
                ->columnSpanFull(),
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
                    ? ' ('.Carbon::parse((string) $documento->fechaincorporacion)->format('d/m/Y').')'
                    : '';

                return [(string) $documento->idDocumento => trim((string) $documento->descripcion).$fecha];
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
            ->where('expediente_id', 'like', '%'.$search.'%')
            ->orderByDesc('ao_ejecucion')
            ->limit(50)
            ->pluck('expediente_id', 'expediente_id')
            ->all();
    }
}
