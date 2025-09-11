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

    protected $primarykey='nummero_Exp';
    
    protected $keyType = 'string';
   
    Function obraInicio(){
        return $this->hasOne (DatosDeInicioDeObras::class);
    }
    Function obraEjecucion(){
        return $this->hasOne(DatosEjecucionObras::class);
    
    }
    Function obraJustificacion(){
        return $this->hasOne(Justificacion_Obra::class);
    
    }
    Function obraCesion(){
        return $this->hasOne(ObraCedida::class);
    
    }
    function documentosexpedientes(){
        return $this->HasMany(DocumentoExpediente::class);
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function estado(): BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class);
    }
    public function ejecucion():BelongsTo
    {
        return $this->belongsTo(FormaEjecucion::class, 'forma_ejecucion', 'COD_CONTRATA' );
    }
}
