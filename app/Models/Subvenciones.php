<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Subvención asociada a una obra o expediente.
 *
 * Tabla 'Subvenciones' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'Expediente' no autoincremental. Modelo de acceso directo
 * sin relaciones Eloquent declaradas.
 */
class Subvenciones extends Model
{
    //
    protected $connection='Obras';
    protected $table='Subvenciones';
    protected $primaryKey='Expediente';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
}
