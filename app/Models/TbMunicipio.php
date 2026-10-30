<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TbProvincias;
use App\Models\Contratista;

/**
 * Catálogo nacional de municipios (para datos de contratistas).
 *
 * Tabla 'TbMunicipios' en la conexión 'Obras' (SQL Server), con clave
 * primaria 'Codigo_Municipio' no autoincremental y sin timestamps.
 * Pertenece a TbProvincias y tiene muchos Contratista.
 */
class TbMunicipio extends Model
{
    protected $connection = 'Obras';
    protected $table = 'TbMunicipios';
    protected $primaryKey = 'Codigo_Municipio';
    public $incrementing = false;
    public $timestamps = false;

    /** Provincia a la que pertenece el municipio. */
    public function provincia()
    {
        return $this->belongsTo(TbProvincias::class, 'Codigo_Provincia', 'PR');
    }

    /** Contratistas con domicilio social en el municipio. */
    public function contratistas()
    {
        return $this->hasMany(Contratista::class, 'Municipio', 'Codigo_Municipio');
    }
}
