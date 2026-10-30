<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Zona geográfica de agrupación de municipios.
 *
 * Tabla 'zonas' en la conexión 'Obras' (SQL Server), con clave primaria
 * string 'CODIGO'. Tiene muchos TablaDeMunicipio.
 */
class Zona extends Model
{
    //
    protected $connection = 'Obras';
    protected $table='zonas';
    protected $primaryKey = 'CODIGO';
    protected $keyType = 'string';
    public $incrementing = false;
    /** Municipios pertenecientes a la zona. */
    public function municipio(){
        return $this->hasMany (TablaDeMunicipio::class,'zona','CODIGO');
    }
}
