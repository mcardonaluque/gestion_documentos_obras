<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property int|string $id
 * @property string $nombre
 * @property bool $con_plantilla
 * @property string|null $plantilla
 * @property string|null $ruta_plantilla
 */
class DocumentoGenerico extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'documento_genericos';

    protected $primaryKey = 'id';

    protected $foreignKey = 'expediente_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'int';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_documento',
        'nombre',
        'fase_doc',
        'fase_siguiente',
        'cod_tipo_doc',
        'descripcion',
        'generado',
        'con_plantilla',
        'plantilla',
        'ruta_plantilla',
        'fasesiguiente',
        'cod_destino',
        'cod_origen',
        'entrada_salida',
        'obligatorio',
        'cod_estado',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'con_plantilla' => 'boolean',
    ];

    public static function normalizePhase(?string $phase): ?string
    {
        $normalized = strtolower(trim((string) ($phase ?? '')));

        if ($normalized === '') {
            return null;
        }

        if (str_contains($normalized, 'justific')) {
            return 'justificacion';
        }

        if (str_contains($normalized, 'ejecuc')) {
            return 'ejecucion';
        }

        if (str_contains($normalized, 'contrat')) {
            return 'contratacion';
        }

        if (str_contains($normalized, 'cesi')) {
            return 'cesion';
        }

        if (str_contains($normalized, 'aproba')) {
            return 'aprobacion';
        }

        if (str_contains($normalized, 'proyect')) {
            return 'proyecto';
        }

        return $normalized;
    }

    public static function phaseLabel(?string $phase): string
    {
        return match (self::normalizePhase($phase)) {
            'proyecto' => 'Proyecto',
            'aprobacion' => 'Aprobación',
            'cesion' => 'Cesión',
            'contratacion' => 'Contratación',
            'ejecucion' => 'Ejecución',
            'justificacion' => 'Justificación',
            default => ucfirst((string) ($phase ?? '')),
        };
    }

    /**
     * Códigos de fase_doc que corresponden a cada fase. ALL aplica a todas las fases.
     */
    private const PHASE_CODES = [
        'proyecto' => ['INI', 'PPR'],
        'aprobacion' => ['APR'],
        'cesion' => ['CES'],
        'contratacion' => [],
        'ejecucion' => ['EJE'],
        'justificacion' => ['JST'],
    ];

    /**
     * Restringe los tipos de documento a los definidos para la fase indicada.
     */
    public function scopeForPhase(Builder $query, ?string $phase): Builder
    {
        return $this->scopeForPhases($query, [$phase]);
    }

    /**
     * Restringe los tipos de documento a los definidos para cualquiera de las fases indicadas.
     *
     * @param array<int, string|null> $phases
     */
    public function scopeForPhases(Builder $query, array $phases): Builder
    {
        $normalizedPhases = array_filter(array_map(self::normalizePhase(...), $phases));

        if ($normalizedPhases === []) {
            return $query;
        }

        $codes = ['ALL'];

        foreach ($normalizedPhases as $normalizedPhase) {
            $codes = array_merge($codes, self::PHASE_CODES[$normalizedPhase] ?? []);
        }

        return $query->whereIn(DB::raw("UPPER(LTRIM(RTRIM(COALESCE({$this->getTable()}.fase_doc, ''))))"), array_unique($codes));
    }

    /**
     * @param array<int, string|null> $phases
     */
    public static function getOptionsForPhases(array $phases): array
    {
        return static::query()
            ->forPhases($phases)
            ->orderBy('nombre')
            ->pluck('nombre', 'id')
            ->toArray();
    }

    /**
     * @param array<int, string|null> $phases
     */
    public function belongsToPhases(array $phases): bool
    {
        return static::query()
            ->whereKey($this->getKey())
            ->forPhases($phases)
            ->exists();
    }

    public function belongsToPhase(?string $phase): bool
    {
        return static::query()
            ->whereKey($this->getKey())
            ->forPhase($phase)
            ->exists();
    }

    public function isJustification(): bool
    {
        $phase = strtolower(trim((string) ($this->fase_doc ?? '')));

        return $phase === 'jst' || str_contains($phase, 'justific');
    }

    public static function getOptionsForPhase(?string $phase): array
    {
        return static::query()
            ->forPhase($phase)
            ->orderBy('nombre')
            ->pluck('nombre', 'id')
            ->toArray();
    }

    public function fasedoc(): BelongsTo
    {
        return $this->belongsTo(FaseDocumento::class, 'fase_doc', 'cod_fase');
    }

    public function fasedocsig(): BelongsTo
    {
        return $this->belongsTo(FaseDocumento::class, 'fase_siguiente', 'cod_fase');
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(DestinoDeDocumentos::class);
    }

    public function tipodoc(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'cod_tipo_doc', 'id');
    }

    public function documtoexpediente(): HasMany
    {
        return $this->hasMany(DocumentoExpediente::class, 'cod_documento', 'id');
    }

    public function estados(): BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class, 'cod_estado', 'cod_estado');
    }

    public function variables(): HasMany
    {
        return $this->hasMany(DocumentoGenericoVariable::class, 'documento_generico_id', 'id');
    }
}


