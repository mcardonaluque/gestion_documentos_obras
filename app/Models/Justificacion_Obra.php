<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Justificacion_Obra extends Model
{
    //
    protected $connection='Obras';
    protected $table='DatosInicioDeObras';
    protected $primaryKey='Expediente';
    protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    public function municipios(){
        return $this->belongsTo(TablaDeMunicipio::class,'municipio','codigo_municipio')
        ->withDefault([
            'nombre_municipio' => 'Sin municipio', // Valor por defecto
        ]);
    }
    public function carrteras():BelongsTo
    {
        return $this->belongsTo(TablaDeCarretera::class, 'carretera', 'Cod_Car' );
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
