<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Solicitud de prórroga de plazo presentada sobre un expediente.
 *
 * Recoge el tipo, alcance y origen de la prórroga solicitada, las fechas
 * límite anterior y nueva, los días concedidos y su estado de tramitación.
 * Tabla 'SolicitudesProrrogas' en la conexión 'Obras' (SQL Server).
 * Se relaciona con Expediente y Team.
 */
class SolicitudProrroga extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'SolicitudesProrrogas';

    protected $fillable = [
        'expediente_id',
        'team_id',
        'tipo_prorroga',
        'alcance_prorroga',
        'origen_prorroga',
        'fecha_limite_anterior',
        'fecha_limite_nueva',
        'dias_concedidos',
        'motivo',
        'observaciones_tramitacion',
        'estado',
    ];

    protected $casts = [
        'fecha_limite_anterior' => 'date',
        'fecha_limite_nueva' => 'date',
        'dias_concedidos' => 'integer',
        'team_id' => 'integer',
    ];

    /** Expediente sobre el que se solicita la prórroga. */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }

    /** Team (ayuntamiento/ámbito) propietario de la solicitud. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
