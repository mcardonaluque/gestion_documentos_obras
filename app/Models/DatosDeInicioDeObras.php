<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatosDeInicioDeObras extends Model

{
    protected $connection='Obras';
    protected $table='DatosInicioDeObras';
    protected $primaryKey='numero_obra';
    protected $foreingKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    use HasFactory;
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    public function municipio(){
        return $this->belongsTo(TablaDeMunicpio::class,'codigo_municipio');
    }
    public function importes()
    {
        return $this->hasOne(ImportesDeObras::class, ['codigo_plan', 'numero_obra', 'ao_ejecucion'], ['codigo_plan', 'numero_obra', 'ao_ejecucion']);
    }
    public function importesPorOrganismo()
    {
        return $this->hasMany(ImportesporOrganismo::class, ['codigo_plan', 'numero_obra', 'ao_ejecucion'], ['codigo_plan', 'numero_obra', 'ao_ejecucion']);
    }
    public function teams():BelongsTo{
        return $this->belongsTo(Team::class);
    }
    
}
