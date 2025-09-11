<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subvenciones extends Model
{
    //
    protected $connection='Obras';
    protected $table='Subvenciones';
    protected $primaryKey='Expediente';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
}
