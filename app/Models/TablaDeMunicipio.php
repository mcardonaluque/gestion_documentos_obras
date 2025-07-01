<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\DatosDeInicioDeObras;

class TablaDeMunicipio extends Model
{
    use HasFactory;
     protected $connection='Obras';
    protected $table='TablaDeMunicipios';
    protected $primaryKey='codigo_municipio';  
    protected $casts = [
        'codigo_municipio' => 'integer', // Convierte codigo_municipio a integer
    ];
    public $incrementing = false;

    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'municipio','codigo_municipio');
    }
    public function zonas(): BelongsTo
    {
        return $this->belongsTo(Zona::class,'zona');
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function getMunicipioAttribute($value)
    {
        return trim($value);
    }
}
