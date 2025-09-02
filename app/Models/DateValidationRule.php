<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateValidationRule extends Model
{
    //
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='dbo.reglas_validacion_fechas';
    protected $primaryKey='id';

    protected $fillable =[];
}
