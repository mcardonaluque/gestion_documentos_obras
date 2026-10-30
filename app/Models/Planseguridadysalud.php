<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Plan de seguridad y salud de una obra.
 *
 * Registra la presentación y aprobación del plan de seguridad y salud
 * exigido antes del inicio de la obra. Tabla 'PlanSeguridadYSalud' en la
 * conexión 'Obras' (SQL Server), con clave primaria string 'expediente_id'.
 * Se relaciona con Expediente, DatosDeInicioDeObras y DatosEjecucionObras.
 */
class Planseguridadysalud extends Model
{
    //
    protected $connection='Obras';
    protected $table='PlanSeguridadYSalud';
    protected $primaryKey='expediente_id';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = true;
    use HasFactory;
    /** Expediente administrativo asociado al plan de seguridad. */
    public function expediente():BelongsTo{
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }
    /** Ficha de inicio de la obra del plan de seguridad. */
    public function obra():BelongsTo{
        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
    }
    /** Datos de ejecución de la obra del plan de seguridad. */
    public function ejecucion():BelongsTo{
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
