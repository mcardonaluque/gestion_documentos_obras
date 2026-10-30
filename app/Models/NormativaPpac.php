<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Normativa general anual del plan provincial.
 *
 * Define las fechas límite y plazos (aprobación, publicación, contratación,
 * justificación, presentación de proyectos y documentación) aplicables a un
 * año de plan, de forma general o específica por Codigo_Plan. Tabla
 * 'NormativaPPAC' en la conexión 'Obras' (SQL Server).
 * Tiene muchos PlazoObraActivo y pertenece a Planes.
 */
class NormativaPpac extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'NormativaPPAC';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'Codigo_Plan',
        'ao_plan',
        'ao_fin_plan',
        'fecha_aprobacion_plan',
        'fecha_publicacion_definitiva',
        'fecha_convenio',
        'fecha_limite_terminacion_plan',
        'fecha_limite_cesion_obra',
        'fecha_limite_contratacion',
        'fecha_limite_justificacion',
        'fecha_limite_presentacion_proyectoD',
        'fecha_limite_presentacion_documentacionD',
        'fecha_documentacionD_asiguiente',
        'fecha_proyectoD_siguiente',
        'fecha_limite_presentacion_proyectoA',
        'fecha_limite_presentacion_documentacionA',
        'fecha_documentacionA_asiguiente',
        'fecha_proyectoA_siguiente',
        'dias_prorroga_max_porcentaje',
        'observaciones',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'ao_plan' => 'integer',
        'ao_fin_plan' => 'integer',
        'fecha_aprobacion_plan' => 'datetime',
        'fecha_publicacion_definitiva' => 'datetime',
        'fecha_convenio' => 'datetime',
        'fecha_limite_terminacion_plan' => 'datetime',
        'fecha_limite_cesion_obra' => 'datetime',
        'fecha_limite_contratacion' => 'datetime',
        'fecha_limite_justificacion' => 'datetime',
        'fecha_limite_presentacion_proyectoD' => 'datetime',
        'fecha_limite_presentacion_documentacionD' => 'datetime',
        'fecha_documentacionD_asiguiente' => 'datetime',
        'fecha_proyectoD_siguiente' => 'datetime',
        'fecha_limite_presentacion_proyectoA' => 'datetime',
        'fecha_limite_presentacion_documentacionA' => 'datetime',
        'fecha_documentacionA_asiguiente' => 'datetime',
        'fecha_proyectoA_siguiente' => 'datetime',
        'dias_prorroga_max_porcentaje' => 'integer',
    ];

    /** Al guardar, recalcula los campos derivados (año fin y fechas límite). */
    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            $model->syncDerivedFields();
        });
    }

    /**
     * Plazos de obra activos gobernados por esta normativa.
     *
     * @return HasMany<PlazoObraActivo, self>
     */
    public function plazosActivos(): HasMany
    {
        return $this->hasMany(PlazoObraActivo::class, 'normativa_ppac_id');
    }

    /**
     * Plan provincial al que aplica específicamente la normativa.
     *
     * @return BelongsTo<Planes, self>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan');
    }

    /**
     * Normativa aplicable a un año y plan: la específica del plan y, si no existe,
     * la general del año (sin Codigo_Plan).
     */
    public static function resolveFor(int|string|null $aoPlan, ?string $codigoPlan = null): ?self
    {
        if (! is_numeric($aoPlan)) {
            return null;
        }

        $codigoPlan = trim((string) $codigoPlan);

        if ($codigoPlan !== '') {
            $especifica = self::query()
                ->where('ao_plan', (int) $aoPlan)
                ->whereRaw('RTRIM(LTRIM(Codigo_Plan)) = ?', [$codigoPlan])
                ->first();

            if ($especifica !== null) {
                return $especifica;
            }
        }

        return self::query()
            ->where('ao_plan', (int) $aoPlan)
            ->where(fn ($query) => $query->whereNull('Codigo_Plan')->orWhereRaw("RTRIM(LTRIM(Codigo_Plan)) = ''"))
            ->first();
    }

    /**
     * Códigos de plan que ya tienen una normativa propia para el mismo año.
     *
     * @return list<string>
     */
    public function codigosPlanAsignados(): array
    {
        return self::query()
            ->where('ao_plan', $this->ao_plan)
            ->whereNotNull('Codigo_Plan')
            ->pluck('Codigo_Plan')
            ->map(fn (mixed $codigo): string => trim((string) $codigo))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Copia las fechas y plazos de esta normativa a cada plan indicado
     * (una fila por año y plan). Devuelve el número de planes asignados.
     *
     * @param  list<string>  $codigosPlan
     */
    public function assignToPlans(array $codigosPlan): int
    {
        $datos = Arr::except($this->getAttributes(), ['id', 'Codigo_Plan', 'created_at', 'updated_at']);
        $asignados = 0;

        DB::connection($this->getConnectionName())->transaction(function () use ($codigosPlan, $datos, &$asignados): void {
            foreach (array_unique(array_map('trim', $codigosPlan)) as $codigo) {
                if ($codigo === '') {
                    continue;
                }

                $existente = self::query()
                    ->where('ao_plan', $this->ao_plan)
                    ->whereRaw('RTRIM(LTRIM(Codigo_Plan)) = ?', [$codigo])
                    ->first();

                if ($existente !== null && $existente->getKey() === $this->getKey()) {
                    $asignados++;

                    continue;
                }

                $destino = $existente ?? new self();
                $destino->forceFill($datos + ['Codigo_Plan' => $codigo]);
                $destino->Codigo_Plan = $codigo;
                $destino->save();
                $asignados++;
            }
        });

        return $asignados;
    }

    /** Recalcula el año fin del plan y las fechas límite derivadas de la publicación. */
    public function syncDerivedFields(): void
    {
        if (is_numeric($this->ao_plan)) {
            $this->ao_fin_plan = self::calculatePlanEndYear((int) $this->ao_plan);
        }

        if ($this->fecha_publicacion_definitiva === null || $this->fecha_publicacion_definitiva === '') {
            return;
        }

        $documentationDate = self::calculateDocumentationDeadline($this->fecha_publicacion_definitiva);
        $projectDate = self::calculateProjectDeadline($this->fecha_publicacion_definitiva);

        $this->fecha_limite_presentacion_documentacionD = $documentationDate;
        $this->fecha_limite_presentacion_documentacionA = $documentationDate;
        $this->fecha_proyectoD_siguiente = $projectDate;
        $this->fecha_proyectoA_siguiente = $projectDate;
    }

    /** Reglas de validación de unicidad del año de plan por Codigo_Plan. */
    public static function aoPlanValidationRules(?int $ignoreId = null, ?string $codigoPlan = null): array
    {
        $rules = ['required', 'integer', 'min:2000', 'max:2100'];
        $codigoPlan = trim((string) $codigoPlan);
        $uniqueRule = Rule::unique('NormativaPPAC', 'ao_plan')
            ->where(fn ($query) => $codigoPlan !== ''
                ? $query->whereRaw('RTRIM(LTRIM(Codigo_Plan)) = ?', [$codigoPlan])
                : $query->whereNull('Codigo_Plan'));

        if ($ignoreId !== null) {
            $uniqueRule = $uniqueRule->ignore($ignoreId);
        }

        $rules[] = $uniqueRule;

        return $rules;
    }

    /** Calcula el año de finalización del plan (año de inicio + 2). */
    public static function calculatePlanEndYear(int $aoPlan): int
    {
        return $aoPlan + 2;
    }

    /** Fecha límite de documentación: publicación definitiva + 2 meses. */
    public static function calculateDocumentationDeadline(string|\DateTimeInterface $publicationDate): Carbon
    {
        return Carbon::parse($publicationDate)->addMonths(2)->startOfDay();
    }

    /** Fecha límite de proyecto: publicación definitiva + 4 meses. */
    public static function calculateProjectDeadline(string|\DateTimeInterface $publicationDate): Carbon
    {
        return Carbon::parse($publicationDate)->addMonths(4)->startOfDay();
    }
}
