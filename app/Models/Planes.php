<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planes extends Model
{
    //
    protected $connection = 'Obras';
    protected $table='Planes';
    protected $primaryKey = 'codigo_plan';
    protected $keyType = 'string';
    public $incrementing = false;
}
