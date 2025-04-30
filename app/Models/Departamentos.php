<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamentos extends Model
{
    //
    protected $connection='Obras';
    protected $table='TablaDeDepartamentos';
    protected $primaryKey='CODIGO_DPTO';
    public $incrementing = false;
}
