<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FasedeProyecto extends Model
{
    //
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='dbo.FasesdeProyectos';
    protected $primaryKey='Expediente';

    protected $fillable =[];
    function expediente(){
        return $this->belongsTo(Expediente::class,'Expediente','Expediente',);
    }
    function proyecto(){
        return $this->belongsTo(Proyecto::class,'Expediente','Expediente',);
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class,'Expediente','Expediente',);
    }
    function servicio_dir(){
        return $this->belongsTo(TablaDeDepartamento::class,'CODIGO_DPTO','servicio_direccion',);
    }
    function servicio_gestor(){
        return $this->belongsTo(TablaDeDepartamento::class,'CODIGO_DPTO','servicio_gestor',);
    }
}
