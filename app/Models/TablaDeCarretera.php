<?php

namespace App\Models;

use Database\Factories\TablaDeCarreteraFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de carreteras provinciales.
 *
 * Tabla 'TablaDeCarrteras' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'CodCar'. Es referenciada desde las obras viarias
 * (DatosDeInicioDeObras y Justificacion_Obra).
 */
class TablaDeCarretera extends Model
{
    /** @use HasFactory<TablaDeCarreteraFactory> */
    use HasFactory;
    protected $connection = 'Obras';
    protected $table='TablaDeCarrteras';
    protected $primaryKey = 'CodCar';
    protected $keyType = 'string';
    public $incrementing = false;
    /** Obras asociadas a la carretera. */
    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'municipio','codigo_municipio');
    }
}
