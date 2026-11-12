<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Solicitud de subsanación de un documento de expediente.
 *
 * Registra el motivo, plazo y fechas de una solicitud de subsanación sobre un
 * documento de `documentacionexpedientes` y, al resolverse, la referencia al
 * documento aportado como subsanación. Tabla `subsanaciones` en la conexión
 * 'Obras'. Pertenece a Expediente, Team y a dos DocumentoExpediente (el
 * requerido y el aportado).
 *
 * @property int $id
 * @property string $expediente_id
 * @property ?string $Codigo_Plan
 * @property ?int $numero_obra
 * @property ?int $subreferencia
 * @property ?int $ao_ejecucion
 * @property string $documento_id
 * @property ?string $documento_subsanacion_id
 * @property string $motivo_subsanacion
 * @property ?int $plazo_subsanacion
 * @property ?Carbon $fecha_solicitud_subsanacion
 * @property ?Carbon $fecha_subsanacion
 * @property ?int $team_id
 * @property ?string $archivo_solicitud
 * @property ?string $tipo_documental_solicitud
 * @property ?string $id_peticion_firma
 * @property ?Carbon $fecha_solicitud_firma
 * @property-read string $estado
 * @property-read ?Carbon $fecha_limite_subsanacion
 */
class Subsanacion extends Model
{
    use HasFactory;

    /** Estado: solicitud pendiente dentro de plazo. */
    public const ESTADO_PENDIENTE = 'pendiente';

    /** Estado: subsanación aportada. */
    public const ESTADO_SUBSANADA = 'subsanada';

    /** Estado: plazo vencido sin subsanar. */
    public const ESTADO_VENCIDA = 'vencida';

    protected $connection = 'Obras';

    protected $table = 'subsanaciones';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'expediente_id',
        'Codigo_Plan',
        'numero_obra',
        'subreferencia',
        'ao_ejecucion',
        'documento_id',
        'documento_subsanacion_id',
        'motivo_subsanacion',
        'plazo_subsanacion',
        'fecha_solicitud_subsanacion',
        'fecha_subsanacion',
        'team_id',
        'archivo_solicitud',
        'tipo_documental_solicitud',
        'id_peticion_firma',
        'fecha_solicitud_firma',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'numero_obra' => 'integer',
        'subreferencia' => 'integer',
        'ao_ejecucion' => 'integer',
        'plazo_subsanacion' => 'integer',
        'fecha_solicitud_subsanacion' => 'date',
        'fecha_subsanacion' => 'date',
        'team_id' => 'integer',
        'fecha_solicitud_firma' => 'datetime',
    ];

    /**
     * Al crear, completa los datos de la obra (plan, obra, subreferencia, año,
     * expediente y team) a partir del documento requerido si no se informaron.
     * Sigue la convención del proyecto (p. ej. AyudaTecnica copia datos de la obra).
     */
    protected static function booted(): void
    {
        static::creating(function (self $subsanacion): void {
            $subsanacion->fillDatosObraDesdeDocumento();
        });

        static::saved(function (self $subsanacion): void {
            $subsanacion->documento()->update([
                'fecha_solicitud_subsanacion' => $subsanacion->fecha_solicitud_subsanacion,
                'fecha_subsanacion' => $subsanacion->fecha_subsanacion,
            ]);
        });
    }

    /** Expediente administrativo al que pertenece la subsanación. */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }

    /** Documento del expediente objeto de la subsanación. */
    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoExpediente::class, 'documento_id', 'idDocumento');
    }

    /** Documento aportado como subsanación (null mientras no se resuelva). */
    public function documentoSubsanacion(): BelongsTo
    {
        return $this->belongsTo(DocumentoExpediente::class, 'documento_subsanacion_id', 'idDocumento');
    }

    /**
     * Cadena ordenada de validadores y firmantes de esta solicitud.
     *
     * @return HasMany<FirmanteDocumento, $this>
     */
    public function firmantes(): HasMany
    {
        return $this->hasMany(FirmanteDocumento::class, 'subsanacion_id')
            ->orderBy('orden');
    }

    /** Plan provincial al que pertenece la obra subsanada. */
    public function planes(): BelongsTo
    {
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan');
    }

    /** Team (ayuntamiento/ámbito) propietario de la solicitud. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scope: solicitudes aún sin subsanar.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePendientes(Builder $query): Builder
    {
        return $query->whereNull('fecha_subsanacion');
    }

    /**
     * Fecha límite para subsanar: fecha de solicitud más el plazo en días.
     */
    public function getFechaLimiteSubsanacionAttribute(): ?Carbon
    {
        if ($this->fecha_solicitud_subsanacion === null || $this->plazo_subsanacion === null) {
            return null;
        }

        return $this->fecha_solicitud_subsanacion->copy()
            ->addDays($this->plazo_subsanacion)
            ->endOfDay();
    }

    /**
     * Estado derivado de la solicitud: subsanada, vencida o pendiente.
     */
    public function getEstadoAttribute(): string
    {
        if ($this->fecha_subsanacion !== null) {
            return self::ESTADO_SUBSANADA;
        }

        $limite = $this->fecha_limite_subsanacion;

        if ($limite !== null && now()->greaterThan($limite)) {
            return self::ESTADO_VENCIDA;
        }

        return self::ESTADO_PENDIENTE;
    }

    /**
     * Copia plan, obra, subreferencia, año, expediente y team desde el
     * documento requerido cuando no vienen informados.
     */
    public function fillDatosObraDesdeDocumento(): void
    {
        if ($this->documento_id === null || $this->documento_id === '') {
            return;
        }

        $documento = $this->documento()->first();

        if (! $documento instanceof DocumentoExpediente) {
            return;
        }

        $this->expediente_id = $this->expediente_id ?: (string) $documento->expediente_id;
        $this->Codigo_Plan ??= $documento->Codigo_Plan !== null ? trim((string) $documento->Codigo_Plan) : null;
        $this->numero_obra ??= is_numeric($documento->referencia) ? (int) $documento->referencia : null;
        $this->subreferencia ??= is_numeric($documento->subreferencia) ? (int) $documento->subreferencia : null;
        $this->ao_ejecucion ??= is_numeric($documento->ao_ejecucion) ? (int) $documento->ao_ejecucion : null;
        $this->team_id ??= $documento->team_id !== null ? (int) $documento->team_id : null;
    }
}
