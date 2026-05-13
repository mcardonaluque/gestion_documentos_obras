<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contratista extends Model
{
    //
    protected $connection='Obras';
    protected $table='TBContratistas';
    protected $primaryKey='Codigo_contratista';

    public $incrementing=false;
    public $timestamps=false;
    public function pendcontratacionobras(){
        return $this->hasMany (PendienteContratacionObra::class,'Codigo_contratista','Codcontratista');
    }
    public function tipoContratista(){
        return $this->belongsTo (TipoContratista::class,'Tipo_contratista','Tipo_contratista');
    }
}
