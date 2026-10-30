<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de formas de ejecución/contratación de obras.
 *
 * Tabla 'FormasDeEjecucion' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'COD_CONTRATA' y sin timestamps. Es referenciado desde
 * Expediente y DatosDeInicioDeObras mediante 'forma_ejecucion'.
 */
class FormaEjecucion extends Model
{
    //
    protected $connection='Obras';
    protected $table='FormasDeEjecucion';
    protected $primaryKey='COD_CONTRATA';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
}
