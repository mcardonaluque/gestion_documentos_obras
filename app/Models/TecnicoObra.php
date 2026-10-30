<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Técnico de obra (director técnico de proyectos).
 *
 * Tabla 'TecnicosObras' en la conexión 'Obras' (SQL Server), con clave
 * primaria 'CodTec'. Tiene muchos Proyecto como director técnico.
 */
class TecnicoObra extends Model
{
    //
    protected $connection='Obras';
    protected $table='TecnicosObras';
    protected $primaryKey='CodTec';
    /** Proyectos dirigidos por este técnico. */
    function proyecto(){
        return $this->HasMany(Proyecto::class,'CodTec','director_tecnico');
    }
}
