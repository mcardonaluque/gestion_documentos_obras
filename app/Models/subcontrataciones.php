<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Subcontratación declarada dentro de una obra.
 *
 * Tabla 'SubContrataciones' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'Expediente' y sin timestamps.
 * Se relaciona con Expediente y Team.
 */
class subcontrataciones extends Model
{
    //
    protected $connection='Obras';
    protected $table='SubContrataciones';
    protected $primaryKey='Expediente';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
    use HasFactory;
    /** Expediente al que pertenece la subcontratación. */
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
