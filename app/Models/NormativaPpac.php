<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * Normativa general anual del plan provincial.
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
        'ao_plan',
        'ao_fin_plan',
        'fecha_aprobacion_plan',
        'fecha_publicacion_definitiva',
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

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            $model->syncDerivedFields();
        });
    }

    /**
     * @return HasMany<PlazoObraActivo, self>
     */
    public function plazosActivos(): HasMany
    {
        return $this->hasMany(PlazoObraActivo::class, 'normativa_ppac_id');
    }

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

    public static function aoPlanValidationRules(?int $ignoreId = null): array
    {
        $rules = ['required', 'integer', 'min:2000', 'max:2100'];
        $uniqueRule = Rule::unique('NormativaPPAC', 'ao_plan');

        if ($ignoreId !== null) {
            $uniqueRule = $uniqueRule->ignore($ignoreId);
        }

        $rules[] = $uniqueRule;

        return $rules;
    }

    public static function calculatePlanEndYear(int $aoPlan): int
    {
        return $aoPlan + 2;
    }

    public static function calculateDocumentationDeadline(string|\DateTimeInterface $publicationDate): Carbon
    {
        return Carbon::parse($publicationDate)->addMonths(2)->startOfDay();
    }

    public static function calculateProjectDeadline(string|\DateTimeInterface $publicationDate): Carbon
    {
        return Carbon::parse($publicationDate)->addMonths(4)->startOfDay();
    }
}
