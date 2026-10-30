<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Obra pendiente de contratación con sus datos de adjudicación.
 *
 * Registra la adjudicación (fecha, importe, contratista) de obras aún no
 * contratadas. Tabla 'PendienteContratacionObras' en la conexión 'Obras'
 * (SQL Server), clave primaria string 'expediente_id' y sin timestamps.
 * Pertenece a Contratista.
 */
class PendienteContratacionObra extends Model
{
    //
    protected $connection='Obras';
    protected $table='PendienteContratacionObras';
    protected $primaryKey='expediente_id';
    protected $keyType = 'string';
    protected $guarded = [];

    protected $casts = [
        'FechaAdjudicacion' => 'datetime',
        'FechaContrato' => 'datetime',
        'ImporteAdjudicacion' => 'decimal:2',
        'ImporteAdjudicacion_Pts' => 'decimal:2',
    ];

    public $incrementing=false;
    public $timestamps=false;
    /** Contratista adjudicatario de la obra. */
    public function contratista(): BelongsTo
    {
        return $this->belongsTo (Contratista::class,'CodContratista','Codigo_contratista');
    }
}
