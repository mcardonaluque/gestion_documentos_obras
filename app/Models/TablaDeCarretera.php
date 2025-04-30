<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaDeCarretera extends Model
{
    /** @use HasFactory<\Database\Factories\TablaDeCarreteraFactory> */
    use HasFactory;
    protected $connection = 'Obras';
    protected $table='TablaDeCarrteras';
    protected $primaryKey = 'CodCar';
    protected $keyType = 'string';
    public $incrementing = false;
}
