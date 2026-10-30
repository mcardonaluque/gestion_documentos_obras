<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit\Framework\Constraint\IsTrue;

/**
 * Aviso generado por el sistema sobre hitos o incidencias de un expediente.
 *
 * Almacena el hito, el tipo de aviso, la obra afectada (plan, número,
 * subreferencia, año) y su resolución. Tabla 'Avisos' en la conexión 'Obras'
 * (SQL Server) con clave primaria autoincremental 'id'.
 * Se relaciona con TiposAviso y Team.
 */
class Aviso extends Model
{
    //
    protected $connection='Obras';
    public $incrementing = true;
    protected $table='Avisos';
    protected $dateFormat = 'Ymd H:i:s';
    protected $primaryKey='id';
   //protected $foreignKey = 'TipoAviso';
    //protected $keyType = 'string';

    protected $fillable = [
        'codigo_hito',
        'fecha_hito',
        'expediente_id',
        'team_id',
        'Referencia',
        'TipoAviso',
        'Codigo_Plan',
        'numero_obra',
        'subreferencia',
        'ao_ejecucion',
        'Usuario',
        'FecSolucion',
        'borrado',
    ];
    protected $casts = [
        'borrado' => 'boolean',
        'FecSolucion' => 'datetime',
    ];
    /** Tipo/catálogo de aviso al que pertenece este aviso. */
    public function tipodeaviso() : BelongsTo{
        return $this->belongsTo(TiposAviso::class, 'TipoAviso', 'TipoAviso');
    }
    /** Team (ayuntamiento/ámbito) propietario del aviso. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
   
}
