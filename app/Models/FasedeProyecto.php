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
    protected $primaryKey='expediente_id';

    protected $fillable =[];
    function expediente(){
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
    function proyecto(){
        return $this->belongsTo(Proyecto::class,'expediente_id','expediente_id',);
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class,'expediente_id','expediente_id',);
    }
    function servicio_dir(){
        return $this->belongsTo(TablaDeDepartamento::class,'CODIGO_DPTO','servicio_direccion',);
    }
    function servicio_gestor(){
        return $this->belongsTo(TablaDeDepartamento::class,'CODIGO_DPTO','servicio_gestor',);
    }
}
