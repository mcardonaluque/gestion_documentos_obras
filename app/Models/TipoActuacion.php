<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoActuacion extends Model
{
    //
    protected $connection = 'Obras';
    protected $table='TiposDeActuacionesDeLosPlanes';
    protected $primaryKey = 'codigo';
    //protected $keyType = 'string';
    public $incrementing = false;
    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'TipoActuacion','codigo');
    }
}

