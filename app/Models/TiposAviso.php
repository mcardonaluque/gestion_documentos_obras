<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Catálogo de tipos de avisos del sistema.
 *
 * Tabla 'TiposAvisos' en la conexión 'Obras' (SQL Server), con clave
 * primaria entera 'TipoAviso' no autoincremental. Tiene muchos Aviso.
 */
class TiposAviso extends Model
{
    //
    protected $connection='Obras';
    protected $table='TiposAvisos';
    protected $primaryKey='TipoAviso';
    protected $keyType = 'int';
    public $incrementing = false;
    protected $fillable = ['TipoAviso','Des'];

    /** Avisos generados de este tipo. */
    public function avisos(): HasMany{
        return $this->hasMany(Aviso::class, 'TipoAviso', 'TipoAviso');
    }
    
}
