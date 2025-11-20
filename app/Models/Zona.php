<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    //
    protected $connection = 'Obras';
    protected $table='zonas';
    protected $primaryKey = 'CODIGO';
    protected $keyType = 'string';
    public $incrementing = false;
    public function municipio(){
        return $this->hasMany (TablaDeMunicipio::class,'zona','CODIGO');
    }
}
