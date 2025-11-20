<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planes extends Model
{
    //
    protected $connection = 'Obras';
    protected $table='Planes';
    protected $primaryKey = 'codigo_plan';
    protected $keyType = 'string';
    public $incrementing = false;
    public function obras():HasMany{
        return $this->hasMany (DatosDeInicioDeObras::class,'codigo_plan','Codigo_Plan');
    }
    public function obrasejecucion():HasMany{
        return $this->hasMany (DatosEjecucionObras::class,'codigo_plan','Codigo_Plan');
    }
    public function certificaciones():HasMany{
        return $this->hasMany (certificaciones::class,'codigo_plan','Codigo_Plan');
    }
    public function getCodigoDescripcionAttribute()
{
    return "{$this->codigo_plan} - {$this->denominacion_plan}";
}
}
