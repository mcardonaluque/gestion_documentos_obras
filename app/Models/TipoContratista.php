<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoContratista extends Model
{
    //

    protected $connection='Obras';
    protected $table='TiposDeContratistas';
    protected $primaryKey='Tipo_contratista';

    public $incrementing=false;
    public $timestamps=false;
    protected $keyType = 'string';
    public function contratistas(){
        return $this->hasMany (Contratista::class,'Tipo_contratista','Tipo_contratista');
    }
}
