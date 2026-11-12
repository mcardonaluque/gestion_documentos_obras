<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Schemas;

use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use App\Models\FirmanteDocumento;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
     * Roles de la aplicación admitidos por NIMEXP en una cadena de firma.
     *
     * @var list<string>
     */
    private const ROLES_FIRMANTES = [
        'Jefe del servicio',
        'Abogado',
        'TAC',
        'Diputado',
    ];

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
                ->default(request()->query('expediente_id'))
                ->getSearchResultsUsing(fn (string $search): array => self::expedienteSearch($search))
                ->getOptionLabelUsing(
                    fn (?string $value): ?string => filled($value)
                        ? Expediente::query()->where('expediente_id', $value)->value('expediente_id')
                        : null
                )
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
                ->options(fn (callable $get): array => self::documentoOptions($expedienteId ?? $get('expediente_id')))
                ->getOptionLabelUsing(
                    fn (string|int|null $value): ?string => self::documentoOptionLabel(
                        $value !== null ? DocumentoExpediente::query()->find($value) : null,
                    ),
                ),
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
            Select::make('tipo_documental_solicitud')
                ->label('Tipo documental')
                ->options(fn (): array => DB::connection('Obras')
                    ->table('catalogo_tipos_documentales')
                    ->orderBy('descripcion')
                    ->get(['identificador', 'descripcion'])
                    ->mapWithKeys(
                        fn (object $tipo): array => [(string) $tipo->identificador => trim((string) $tipo->descripcion)]
                    )
                    ->all())
                ->default('TD01')
                ->searchable()
                ->preload()
                ->required()
                ->helperText('Se muestra el nombre del tipo; se enviará su código NIMBUS al tramitador.'),
            Repeater::make('firmantes')
                ->label('Cadena de validación y firma')
                ->relationship()
                ->schema([
                    Select::make('id_usuario')
                        ->label('Usuario')
                        ->options(fn (): array => User::query()
                            ->role(self::ROLES_FIRMANTES)
                            ->whereNotNull('id_usuario')
                            ->where('id_usuario', '<>', '')
                            ->orderBy('name')
                            ->get(['id_usuario', 'name'])
                            ->mapWithKeys(
                                fn (User $user): array => [trim((string) $user->id_usuario) => $user->name]
                            )
                            ->all())
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (?string $state, callable $set): void {
                            $roles = self::rolesFirmante($state);

                            $set('id_rol', count($roles) === 1 ? array_key_first($roles) : null);
                        })
                        ->required(),
                    Select::make('id_rol')
                        ->label('Rol para la firma')
                        ->options(fn (callable $get): array => self::rolesFirmante($get('id_usuario')))
                        ->disabled(fn (callable $get): bool => blank($get('id_usuario')))
                        ->required(),
                    Select::make('tipo_firma')
                        ->label('Acción')
                        ->options([
                            FirmanteDocumento::TIPO_VALIDACION => 'Valida',
                            FirmanteDocumento::TIPO_FIRMA => 'Firma',
                        ])
                        ->default(FirmanteDocumento::TIPO_FIRMA)
                        ->required(),
                ])
                ->orderColumn('orden')
                ->defaultItems(1)
                ->addActionLabel('Añadir firmante')
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
            ->get(['idDocumento', 'nombre_documento', 'descripcion', 'fechaincorporacion'])
            ->mapWithKeys(fn (DocumentoExpediente $documento): array => [
                (string) $documento->idDocumento => self::documentoOptionLabel($documento),
            ])
            ->all();
    }

    /**
     * Obtiene los roles del usuario que se pueden enviar a NIMEXP.
     *
     * @return array<string, string>
     */
    private static function rolesFirmante(?string $idUsuario): array
    {
        if (blank($idUsuario)) {
            return [];
        }

        $usuario = User::query()
            ->whereRaw('RTRIM(id_usuario) = ?', [trim($idUsuario)])
            ->with('roles:id,name')
            ->first();

        if (! $usuario instanceof User) {
            return [];
        }

        return $usuario->roles
            ->filter(fn ($role): bool => in_array($role->name, self::ROLES_FIRMANTES, true))
            ->mapWithKeys(fn ($role): array => [$role->name => $role->name])
            ->all();
    }

    /** Devuelve la etiqueta legible de un documento de expediente. */
    private static function documentoOptionLabel(?DocumentoExpediente $documento): ?string
    {
        if (! $documento instanceof DocumentoExpediente) {
            return null;
        }

        $nombre = trim((string) ($documento->nombre_documento ?: $documento->descripcion));
        $nombre = $nombre !== '' ? $nombre : 'Documento '.$documento->idDocumento;
        $fecha = filled($documento->fechaincorporacion)
            ? ' ('.Carbon::parse((string) $documento->fechaincorporacion)->format('d/m/Y').')'
            : '';

        return $nombre.$fecha;
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
