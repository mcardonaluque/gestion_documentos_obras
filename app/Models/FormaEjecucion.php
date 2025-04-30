<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaEjecucion extends Model
{
    //
    protected $connection='Obras';
    protected $table='FormasDeEjecucion';
    protected $primaryKey='COD_CONTRATA';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
}
