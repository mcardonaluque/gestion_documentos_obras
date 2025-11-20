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
    protected $primaryKey='expediente_id';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = true;
    use HasFactory;
    public function expediente():BelongsTo{
        return $this->belongsTo(Expediente::class);
    }
    public function obra():BelongsTo{
        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'Expediente');
    }
    public function ejecucion():BelongsTo{
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id', 'Expediente');
    }
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
