<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PorcentajesProyectos extends Model
{
    //
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='dbo.PorcentajesProyecto';
    protected $primaryKey='Expediente';

    protected $fillable =[];
}
