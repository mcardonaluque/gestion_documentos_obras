<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * Acta de comprobación de replanteo de una obra.
 *
 * Registra las fechas de inicio, final y prórroga del acta, además de los
 * indicadores de impresión y recepción. Tabla 'ActasDeReplanteo' en la
 * conexión 'Obras' (SQL Server), con clave primaria string 'expediente_id'.
 * Se relaciona con DatosEjecucionObras por el expediente.
 */
class actadereplanteo extends Model
{
    protected $connection = 'Obras';
    protected $table = 'ActasDeReplanteo';
    protected $primaryKey = 'expediente_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'expediente_id',
        'Fecha_Inicio_Acta_Replanteo',
        'Fecha_Final_Acta_Replanteo',
        'Fecha_Prorroga_Acta_Replanteo',
        'Indicador_Impresion_AR',
        'Indicador_Recepcion_AR',
        'team_id',
    ];

    protected $casts = [
        'Fecha_Inicio_Acta_Replanteo' => 'datetime',
        'Fecha_Final_Acta_Replanteo' => 'datetime',
        'Fecha_Prorroga_Acta_Replanteo' => 'datetime',
        'Indicador_Impresion_AR' => 'boolean',
        'Indicador_Recepcion_AR' => 'boolean',
    ];

    /** Datos de ejecución de la obra a la que pertenece este acta de replanteo. */
    public function ejecucion(): BelongsTo
    {
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');
    }
}
