<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendienteContratacionObra extends Model
{
    //
    protected $connection='Obras';
    protected $table='PendienteContratacionObras';
    protected $primaryKey='expediente_id';

    public $incrementing=false;
    public $timestamps=false;
    public function contratista(){
        return $this->belongsTo (Contratista::class,'Codcontratista','Codigo_contratista');
    }
    public function expediente(){
        return $this->belongsTo (Expediente::class,'expediente_id','expediente_id');
    }
    public function obras()
    {
        return $this->hasMany(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
    }
}
