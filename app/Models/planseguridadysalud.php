<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planseguridadysalud extends Model
{
    //
    protected $connection='Obras';
    protected $table='PlanSeguridadYSalud';
    protected $primaryKey='Expediente';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
    use HasFactory;
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
