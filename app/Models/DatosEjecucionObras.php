<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DatosEjecucionObras extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='Datos_Ejecucion_Obras';
    protected $primaryKey='Expediente';
    
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    function importes():HasOne   {
        return $this->hasOne(ImportesDeObras::class, 'Expediente','Expediente');
    }
    public function importesPorOrganismo():HasMany
    {
        return $this->hasMany(ImportesporOrganismo::class, 'Expediente', 'Expediente' );
    }
    
    public function planes():BelongsTo
    {
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan' );
    }
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class);
    }
}
