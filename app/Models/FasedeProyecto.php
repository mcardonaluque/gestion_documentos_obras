<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Fase de tramitación de un proyecto técnico.
 *
 * Registra los hitos de cada fase del proyecto y los servicios implicados.
 * Tabla 'dbo.FasesdeProyectos' en la conexión 'Obras' (SQL Server), con
 * clave primaria 'expediente_id'. Se relaciona con Expediente, Proyecto,
 * DatosDeInicioDeObras y TablaDeDepartamento.
 */
class FasedeProyecto extends Model
{
    //
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='dbo.FasesdeProyectos';
    protected $primaryKey='expediente_id';

    protected $fillable =[];
    /** Expediente administrativo asociado a la fase. */
    function expediente(){
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
    /** Proyecto técnico al que pertenece la fase. */
    function proyecto(){
        return $this->belongsTo(Proyecto::class,'expediente_id','expediente_id',);
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    /** Ficha de inicio de obra asociada a la fase. */
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class,'expediente_id','expediente_id',);
    }
    /** Servicio/departamento responsable de la dirección. */
    function servicio_dir(){
        return $this->belongsTo(TablaDeDepartamento::class,'CODIGO_DPTO','servicio_direccion',);
    }
    /** Servicio/departamento gestor del proyecto. */
    function servicio_gestor(){
        return $this->belongsTo(TablaDeDepartamento::class,'CODIGO_DPTO','servicio_gestor',);
    }
}
