<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tipo o categoría de contratista.
 *
 * Tabla 'TiposDeContratistas' en la conexión 'Obras' (SQL Server), con
 * clave primaria string 'Tipo_contratista' y sin timestamps.
 * Tiene muchos Contratista.
 */
class TipoContratista extends Model
{
    //

    protected $connection='Obras';
    protected $table='TiposDeContratistas';
    protected $primaryKey='Tipo_contratista';

    public $incrementing=false;
    public $timestamps=false;
    protected $keyType = 'string';
    /** Contratistas clasificados con este tipo. */
    public function contratistas(){
        return $this->hasMany (Contratista::class,'Tipo_contratista','Tipo_contratista');
    }
}
