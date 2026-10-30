<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\DatosDeInicioDeObras;

/**
 * Catálogo de municipios de la provincia.
 *
 * Tabla 'TablaDeMunicipios' en la conexión 'Obras' (SQL Server), con clave
 * primaria entera 'codigo_municipio' no autoincremental. Tiene muchas obras
 * y pertenece a una Zona y a un Team.
 */
class TablaDeMunicipio extends Model
{
    use HasFactory;
     protected $connection='Obras';
    protected $table='TablaDeMunicipios';
    protected $primaryKey='codigo_municipio';  
    protected $casts = [
        'codigo_municipio' => 'integer', // Convierte codigo_municipio a integer
    ];
    public $incrementing = false;

    /** Obras ubicadas en el municipio. */
    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'municipio','codigo_municipio');
    }
    /** Zona geográfica a la que pertenece el municipio. */
    public function zonas(): BelongsTo
    {
        return $this->belongsTo(Zona::class,'zona','CODIGO');
    }
    /** Team (ayuntamiento/ámbito) asociado al municipio. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    /** Accessor: nombre del municipio sin espacios sobrantes. */
    public function getMunicipioAttribute($value)
    {
        return trim($value);
    }
}
