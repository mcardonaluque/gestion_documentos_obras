<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Plan provincial de obras y servicios.
 *
 * Catálogo maestro de planes (PPAC, planes de cooperación...) al que se
 * adscriben obras, expedientes y certificaciones. Tabla 'Planes' en la
 * conexión 'Obras' (SQL Server), con clave primaria string 'codigo_plan'.
 * Tiene muchas obras, ejecuciones, certificaciones y expedientes.
 */
class Planes extends Model
{
    //
    protected $connection = 'Obras';
    protected $table='Planes';
    protected $primaryKey = 'codigo_plan';
    protected $keyType = 'string';
    public $incrementing = false;
    /** Fichas de inicio de obra adscritas al plan. */
    public function obras():HasMany{
        return $this->hasMany (DatosDeInicioDeObras::class,'codigo_plan','Codigo_Plan');
    }
    /** Datos de ejecución de las obras del plan. */
    public function obrasejecucion():HasMany{
        return $this->hasMany (DatosEjecucionObras::class,'codigo_plan','Codigo_Plan');
    }
    /** Certificaciones económicas de las obras del plan. */
    public function certificaciones():HasMany{
        return $this->hasMany (certificaciones::class,'codigo_plan','Codigo_Plan');
    }
     /** Expedientes administrativos adscritos al plan. */
     public function expedientes():HasMany{
        return $this->hasMany (Expediente::class,'codigo_plan','Codigo_Plan');
    }
     /** Documentos (fichas de obra) asociados al plan. */
     public function documentos():HasMany{
        return $this->hasMany (DatosDeInicioDeObras::class,'codigo_plan','Codigo_Plan');
    }
     /** Proyectos (fichas de obra) asociados al plan. */
     public function proyectos():HasMany{
        return $this->hasMany (DatosDeInicioDeObras::class,'codigo_plan','Codigo_Plan');
    }
    /** Accessor: código y denominación del plan concatenados. */
    public function getCodigoDescripcionAttribute()
    {
        return "{$this->codigo_plan} - {$this->denominacion_plan}";
    }
}
