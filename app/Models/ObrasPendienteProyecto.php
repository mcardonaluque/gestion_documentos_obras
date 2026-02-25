<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObrasPendienteProyecto extends Model
{
    protected $table = 'V_ObrasPendientesProyecto';

    protected $primaryKey = 'expediente_id'; // o el que corresponda
    public $incrementing = false;
    protected $keyType = 'string';

    // Campos que devuelve la vista:
    protected $fillable = [

        // ... lo que tengas
    ];
    public function expediente():BelongsTo {
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
     public function municipios(): BelongsTo{
        return $this->belongsTo(TablaDeMunicipio::class,'municipio','codigo_municipio')
        ->withDefault([
            'nombre_municipio' => 'Sin municipio', // Valor por defecto
        ]);
    }
     function servicioDir(){
        return $this->belongsTo(TablaDeDepartamento::class,'servicio_direccion','CODIGO_DPTO',);
    }
}
