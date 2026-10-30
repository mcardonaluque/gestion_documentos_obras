<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de tipos de justificante para certificaciones.
 *
 * Tabla 'TiposJustificante' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'id' no autoincremental. Tiene muchas certificaciones.
 */
class tiposjustificante extends Model
{
    //
    protected $connection='Obras';
    protected $table='TiposJustificante';
    protected $primaryKey='id';
    protected $keyType = 'string';
    public $incrementing = false;
    /** Certificaciones que usan este tipo de justificante. */
    public function certificaciones(){
        return $this->Hasmany(certificaciones::class);
    }
}
