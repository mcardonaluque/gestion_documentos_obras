<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Obra pendiente de presentación de proyecto (vista de solo lectura).
 *
 * Se apoya en la vista SQL 'V_ObrasPendientesProyecto' (conexión por
 * defecto) con clave primaria string 'expediente_id'. Se relaciona con
 * Expediente, TablaDeMunicipio y TablaDeDepartamento.
 */
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
    /** Expediente administrativo de la obra pendiente. */
    public function expediente():BelongsTo {
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
     /** Municipio de la obra, con valor por defecto si no existe. */
     public function municipios(): BelongsTo{
        return $this->belongsTo(TablaDeMunicipio::class,'municipio','codigo_municipio')
        ->withDefault([
            'nombre_municipio' => 'Sin municipio', // Valor por defecto
        ]);
    }
     /** Servicio/departamento responsable de la dirección. */
     function servicioDir(){
        return $this->belongsTo(TablaDeDepartamento::class,'servicio_direccion','CODIGO_DPTO',);
    }
}
