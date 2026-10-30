<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Departamento de la diputación (catálogo auxiliar).
 *
 * Tabla 'TablaDeDepartamentos' en la conexión 'Obras' (SQL Server),
 * con clave primaria 'CODIGO_DPTO' no autoincremental. Es referenciado
 * desde AyudaTecnica como departamento redactor o director.
 */
class Departamentos extends Model
{
    //
    protected $connection='Obras';
    protected $table='TablaDeDepartamentos';
    protected $primaryKey='CODIGO_DPTO';
    public $incrementing = false;
}
