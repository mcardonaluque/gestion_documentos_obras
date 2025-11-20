<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    //
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='Proyectos';
    protected $primaryKey='expediente_id';

    protected $fillable =[];
    function expediente(){
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
   
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    
    function servicioRed(){
        return $this->belongsTo(TablaDeDepartamento::class,'servicio_redactor','CODIGO_DPTO',);
    }
    function municipio(){
        return $this->belongsTo(TablaDeMunicipio::class,'CODIGO_MUNICIPIO','codigo_municipio',);
    }
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class,'expediente_id','expediente_id');
    }
    function servicioDir(){
        return $this->belongsTo(TablaDeDepartamento::class,'servicio_direccion','CODIGO_DPTO',);
    }
    function servicioGestor(){
        return $this->belongsTo(TablaDeDepartamento::class,'Servicio_Gestor','CODIGO_DPTO',);
    }
    function tecnicoDir(){
        return $this->belongsTo(TecnicoObra::class,'director_tecnico','CodTec',);
    }
    function fases(){
        return $this->hasMany(FaseDeProyecto::class,'expediente_id','expediente_id',);
    }
}
