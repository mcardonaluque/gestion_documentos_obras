<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TecnicoObra extends Model
{
    //
    protected $connection='Obras';
    protected $table='TecnicosObras';
    protected $primaryKey='CodTec';
}
