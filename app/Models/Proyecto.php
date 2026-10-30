<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Proyecto técnico de una obra.
 *
 * Contiene los datos de redacción y dirección facultativa del proyecto:
 * servicios, organismos, técnico director y estado. Tabla 'Proyectos' en la
 * conexión 'Obras' (SQL Server), con clave primaria string 'expediente_id'.
 * Se relaciona con Expediente, DatosDeInicioDeObras, TablaDeDepartamento,
 * organismo, TecnicoObra, FasedeProyecto, TablaDeEstados y Planes.
 */
class Proyecto extends Model
{
    //
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='Proyectos';
    protected $primaryKey='expediente_id';
     protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable =[];
    /** Expediente administrativo del proyecto. */
    function expediente(){
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }

    /** Team (ayuntamiento/ámbito) propietario del proyecto. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /** Servicio/departamento redactor del proyecto. */
    function servicioRed(){
        return $this->belongsTo(TablaDeDepartamento::class,'Servicio_redactor','CODIGO_DPTO',);
    }
    /** Municipio en el que se ubica el proyecto. */
    function municipio(){
        return $this->belongsTo(TablaDeMunicipio::class,'CODIGO_MUNICIPIO','codigo_municipio',);
    }
    /** Ficha de inicio de la obra proyectada. */
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class,'expediente_id','expediente_id');
    }
    /** Servicio/departamento responsable de la dirección facultativa. */
    function servicioDir(){
        return $this->belongsTo(TablaDeDepartamento::class,'servicio_direccion','CODIGO_DPTO',);
    }
    /** Servicio/departamento gestor del proyecto. */
    function servicioGestor(){
        return $this->belongsTo(TablaDeDepartamento::class,'Servicio_Gestor','CODIGO_DPTO',);
    }
    /** Técnico director de la obra. */
    function tecnicoDir(){
        return $this->belongsTo(TecnicoObra::class,'director_tecnico','CodTec',);
    }
    /** Fases de tramitación del proyecto. */
    function fases(){
        return $this->hasMany(FasedeProyecto::class,'expediente_id','expediente_id',);
    }
     /** Organismo redactor del proyecto. */
     public function organismoRed(): BelongsTo
    {
        return $this->belongsTo(organismo::class,'organismo_redactor','codigo_organismo',);
    }
    /** Organismo responsable de la dirección del proyecto. */
    public function organismoDir(): BelongsTo
    {
        return $this->belongsTo(organismo::class,'organismo_direccion','codigo_organismo',);
    }
    /** Estado de tramitación del proyecto. */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class,'cod_estado','estado_proyecto',);
    }
    /** Plan provincial al que pertenece el proyecto. */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Planes::class,'Codigo_Plan','codigo_plan',);
    }

}

