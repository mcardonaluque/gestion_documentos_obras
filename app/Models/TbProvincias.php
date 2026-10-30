<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TbMunicipio;

/**
 * Catálogo nacional de provincias (para datos de contratistas).
 *
 * Tabla 'TbProvincias' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'PR' y sin timestamps. Tiene muchos TbMunicipio.
 */
class TbProvincias extends Model
{
    protected $connection = 'Obras';
    protected $table = 'TbProvincias';
    protected $primaryKey = 'PR';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    /** Municipios pertenecientes a la provincia. */
    public function municipios()
    {
        return $this->hasMany(TbMunicipio::class, 'Codigo_Provincia', 'PR');
    }
}
