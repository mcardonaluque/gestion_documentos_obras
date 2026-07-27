<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Plazos operativos vigentes por expediente y fase.
 *
 * Cada registro representa un plazo activo sobre el que se pueden aplicar
 * prórrogas de normativa, ejecución o justificación.
 */
class PlazoObraActivo extends Model
{
    use HasFactory;

    /** Conexión SQL Server usada por el módulo de obras. */
    protected $connection = 'Obras';

    /** Nombre real de la tabla de plazos activos. */
    protected $table = 'PlazosObraActivos';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'expediente_id',
        'normativa_ppac_id',
        'fase',
        'fecha_inicio',
        'fecha_fin',
        'dias_base',
        'dias_prorroga_acumulados',
        'activo',
        'fuente_ultima_actualizacion',
        'team_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'dias_base' => 'integer',
        'dias_prorroga_acumulados' => 'integer',
        'activo' => 'boolean',
        'team_id' => 'integer',
    ];

    /**
     * Normativa PPAC que origina o gobierna este plazo.
     *
     * @return BelongsTo<NormativaPpac, self>
     */
    public function normativa(): BelongsTo
    {
        return $this->belongsTo(NormativaPpac::class, 'normativa_ppac_id');
    }

    /**
     * Expediente propietario del plazo.
     *
     * @return BelongsTo<Expediente, self>
     */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }
}
