<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Organismo participante en la financiación o gestión de las obras.
 *
 * Catálogo de organismos (diputación, ministerios, consejerías...) con su
 * denominación y agrupación. Tabla 'TablaDeOrganismos' en la conexión
 * 'Obras' (SQL Server), clave primaria string 'codigo_organismo'.
 * Tiene muchos Proyecto como organismo redactor o director.
 */
class organismo extends Model
{
    //
     protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='TablaDeOrganismos';
    protected $primaryKey='codigo_organismo';
     protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable =['codigo_organismo','denominacion','direccion','abreviatura','agrupacion','orden'];

     /** Proyectos en los que este organismo actúa como redactor. */
     function proyectosRed(){
        return $this->hasMany(Proyecto::class,'codigo_organismo','organismo_redactor',);
    }

    /** Proyectos en los que este organismo actúa como director. */
    function proyectosDir(){
        return $this->hasMany(Proyecto::class,'codigo_organismo','organismo_direccion',);
    }

}
