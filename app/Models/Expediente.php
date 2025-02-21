<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expediente extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='Expedientes';

    protected $primarykey='numExpediente';
    
    protected $keyType = 'string';
   
    Function obraInicio(){
        return $this->hasOne (DatosDeInicioDeObras::class);
    }
    Function obraEjecucion(){
        return $this->hasOne(Datos_Ejecucion_Obras::class);
    
    }
    function documentosexpedientes(){
        return $this->HasMany(DocumentoExpediente::class);
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
