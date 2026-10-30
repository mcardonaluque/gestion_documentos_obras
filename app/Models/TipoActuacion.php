<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tipo de actuación de las obras dentro de los planes.
 *
 * Tabla 'TiposDeActuacionesDeLosPlanes' en la conexión 'Obras'
 * (SQL Server), con clave primaria 'codigo' no autoincremental.
 * Tiene muchas DatosDeInicioDeObras.
 */
class TipoActuacion extends Model
{
    //
    protected $connection = 'Obras';
    protected $table='TiposDeActuacionesDeLosPlanes';
    protected $primaryKey = 'codigo';
    //protected $keyType = 'string';
    public $incrementing = false;
    /** Obras clasificadas con este tipo de actuación. */
    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'TipoActuacion','codigo');
    }
}

