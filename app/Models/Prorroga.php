<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo principal de la tabla de prórrogas de obra.
 *
 * Conserva el esquema histórico de la base de datos y añade campos de gestión
 * para la tramitación documental, seguimiento de plazos y trazabilidad CSV.
 */
class Prorroga extends Model
{
    /** Conexión SQL Server usada por el módulo de obras. */
    protected $connection = 'Obras';

    /** Nombre real de la tabla histórica. */
    protected $table = 'Prorrogas';

    /** Clave primaria no autoincremental basada en la secuencia NumSec. */
    protected $primaryKey = 'NumSec';

    /** Indica que la clave primaria no se genera automáticamente. */
    public $incrementing = false;

    /** Tipo entero para la clave primaria. */
    protected $keyType = 'int';

    protected $fillable = [
        'PlanObra',
        'NumObra',
        'SubRef',
        'AoObra',
        'NumSec',
        'FecPeticionProrrogaDeDip',
        'FecSolicitudProrrogaDeCont',
        'FecProrroga',
        'FecSolicitudInfTec',
        'FecInfTec',
        'FecComInf',
        'FecComGob',
        'FecDecreto',
        'NumDecreto',
        'MotivoProrroga',
        'expediente_id',
        'team_id',
        'tipo_prorroga',
        'alcance_prorroga',
        'origen_prorroga',
        'fecha_notificacion_ayto',
        'fecha_limite_anterior',
        'fecha_limite_nueva',
        'dias_concedidos',
        'csv_informe_rof',
        'csv_propuesta',
        'csv_decreto',
        'fecha_firma_informe_rof',
        'fecha_firma_propuesta',
        'fecha_firma_decreto',
        'observaciones_tramitacion',
    ];

    protected $casts = [
        'NumObra' => 'integer',
        'SubRef' => 'integer',
        'AoObra' => 'integer',
        'NumSec' => 'integer',
        'FecPeticionProrrogaDeDip' => 'datetime',
        'FecSolicitudProrrogaDeCont' => 'datetime',
        'FecProrroga' => 'datetime',
        'FecSolicitudInfTec' => 'datetime',
        'FecInfTec' => 'datetime',
        'FecComInf' => 'datetime',
        'FecComGob' => 'datetime',
        'FecDecreto' => 'datetime',
        'fecha_notificacion_ayto' => 'date',
        'fecha_limite_anterior' => 'date',
        'fecha_limite_nueva' => 'date',
        'dias_concedidos' => 'integer',
        'fecha_firma_informe_rof' => 'date',
        'fecha_firma_propuesta' => 'date',
        'fecha_firma_decreto' => 'date',
    ];

    /**
     * Relación con el expediente de ejecución asociado.
     */
    public function ejecucion(): BelongsTo
    {
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');
    }

    /**
     * Relación con el expediente base.
     */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }

    /**
     * Plazos activos afectados por esta prórroga.
     */
    public function plazosActivos(): HasMany
    {
        return $this->hasMany(PlazoObraActivo::class, 'expediente_id', 'expediente_id');
    }
}
