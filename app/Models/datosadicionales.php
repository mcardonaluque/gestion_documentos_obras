<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Datos adicionales complementarios de una obra o expediente.
 *
 * Tabla 'Datosadicionales' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'expediente_id' y sin timestamps.
 * Se relaciona con Expediente, TablaDeEstados y Team.
 */
class datosadicionales extends Model
{
    //
    protected $connection='Obras';
    protected $table='Datosadicionales';
    protected $primaryKey='expediente_id';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
    use HasFactory;
    /** Expediente al que pertenecen los datos adicionales. */
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    /** Estado de la obra asociado a los datos adicionales. */
    public function estados():BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class, 'codigo_estado_obra', 'cod_estado' );
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
