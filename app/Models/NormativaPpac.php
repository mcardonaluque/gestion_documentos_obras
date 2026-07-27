<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'fecha_publicacion_definitiva',
        'fecha_limite_terminacion_plan',
        'fecha_limite_justificacion_plan',
        'fecha_limite_presentacion_proyecto',
        'fecha_limite_presentacion_documentacion',
        'fecha_cesion_proyecto',
        'fecha_cesion_documentacion',
        'dias_prorroga_max_porcentaje',
        'observaciones',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'ao_plan' => 'integer',
        'ao_fin_plan' => 'integer',
        'fecha_publicacion_definitiva' => 'datetime',
        'fecha_limite_terminacion_plan' => 'datetime',
        'fecha_limite_justificacion_plan' => 'datetime',
        'fecha_limite_presentacion_proyecto' => 'datetime',
        'fecha_limite_presentacion_documentacion' => 'datetime',
        'fecha_cesion_proyecto' => 'datetime',
        'fecha_cesion_documentacion' => 'datetime',
        'dias_prorroga_max_porcentaje' => 'integer',
    ];

    /**
     * @return HasMany<PlazoObraActivo, self>
     */
    public function plazosActivos(): HasMany
    {
        return $this->hasMany(PlazoObraActivo::class, 'normativa_ppac_id');
    }
}
