<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public static function getOptionsForPhase(?string $phase): array
    {
        $query = static::query()->orderBy('nombre');
        $normalizedPhase = self::normalizePhase($phase);

        if ($normalizedPhase) {
            $query->where(function ($subQuery) use ($normalizedPhase): void {
                match ($normalizedPhase) {
                    'justificacion' => $subQuery->whereIn('fase_doc', ['ALL', 'JST'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%justific%']),
                    'ejecucion' => $subQuery->whereIn('fase_doc', ['ALL', 'EJE'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%ejecuc%']),
                    'contratacion' => $subQuery->whereIn('fase_doc', ['ALL'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%contrat%']),
                    'cesion' => $subQuery->whereIn('fase_doc', ['ALL', 'CES'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%cesi%']),
                    'aprobacion' => $subQuery->whereIn('fase_doc', ['ALL', 'APR'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%aproba%']),
                    default => $subQuery->where(function ($projectQuery): void {
                        $projectQuery
                            ->whereIn('fase_doc', ['ALL', 'INI'])
                            ->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%proyect%'])
                            ->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%proyecto%']);
                    }),
                };
            });
        }

        return $query->pluck('nombre', 'id')->toArray();
    }

    public function scopeForPhase(Builder $query, ?string $phase): Builder
    {
        $normalizedPhase = self::normalizePhase($phase);

        if (! $normalizedPhase) {
            return $query;
        }

        return $query->where(function ($subQuery) use ($normalizedPhase): void {
            match ($normalizedPhase) {
                'justificacion' => $subQuery->whereIn('fase_doc', ['ALL', 'JST'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%justific%']),
                'ejecucion' => $subQuery->whereIn('fase_doc', ['ALL', 'EJE'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%ejecuc%']),
                'contratacion' => $subQuery->whereIn('fase_doc', ['ALL'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%contrat%']),
                'cesion' => $subQuery->whereIn('fase_doc', ['ALL', 'CES'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%cesi%']),
                'aprobacion' => $subQuery->whereIn('fase_doc', ['ALL', 'APR'])->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%aproba%']),
                default => $subQuery->where(function ($projectQuery): void {
                    $projectQuery
                        ->whereIn('fase_doc', ['ALL', 'INI'])
                        ->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%proyect%'])
                        ->orWhereRaw("LOWER(COALESCE(fase_doc, '')) LIKE ?", ['%proyecto%']);
                }),
            };
        });
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


