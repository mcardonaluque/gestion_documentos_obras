<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de estados de tramitación del sistema.
 *
 * Define los estados posibles de obras, expedientes, proyectos y
 * certificaciones, con indicadores de los módulos a los que aplican.
 * Tabla 'TablaDeEstados' en la conexión 'Obras' (SQL Server), clave
 * primaria string 'cod_estado' y sin timestamps.
 */
class TablaDeEstados extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='TablaDeEstados';
    protected $primaryKey='cod_estado';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable=[
        'cod_estado',
        'estado',
        'estado_abrev',
        'moduloini',
        'modulofin',
        'Tabla',
        'Planes',
        'SubvRP',
        'Contratacion',
        'Proyecto',
        'Obras',
        'Certificaciones',
    ];

    /** Obras que se encuentran en este estado. */
    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'codigo_estado_obra','cod_estado');
    }
    /** Expedientes que se encuentran en este estado. */
    public function expedientes(){
        return $this->hasMany (Expediente::class,'cod_estado','cod_estado');
    }
    /** Tipos de documentos genéricos asociados a este estado. */
    public function documentosgenerico(){
        return $this->hasMany (DocumentoGenerico::class,'cod_estado','cod_estado');
    }
      /** Proyectos que se encuentran en este estado. */
      public function proyecto(){
        return $this->hasMany (Proyecto::class,'estado_proyecto','cod_estado');
    }
}
