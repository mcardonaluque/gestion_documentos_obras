<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

     function proyectosRed(){
        return $this->hasMany(Proyecto::class,'codigo_organismo','organismo_redactor',);
    }

    function proyectosDir(){
        return $this->hasMany(Proyecto::class,'codigo_organismo','organismo_direccion',);
    }

}
