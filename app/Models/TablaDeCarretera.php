<?php

namespace App\Models;

use Database\Factories\TablaDeCarreteraFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaDeCarretera extends Model
{
    /** @use HasFactory<TablaDeCarreteraFactory> */
    use HasFactory;
    protected $connection = 'Obras';
    protected $table='TablaDeCarrteras';
    protected $primaryKey = 'CodCar';
    protected $keyType = 'string';
    public $incrementing = false;
    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'municipio','codigo_municipio');
    }
}
