<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Vista de la obra orientada a la fase de justificación.
 *
 * Reutiliza la tabla 'DatosInicioDeObras' en la conexión 'Obras'
 * (SQL Server) con clave primaria string 'Expediente' para gestionar la
 * justificación económica. Se relaciona con Expediente, TablaDeMunicipio,
 * TablaDeCarretera y Team.
 */
class Justificacion_Obra extends Model
{
    //
    protected $connection='Obras';
    protected $table='DatosInicioDeObras';
    protected $primaryKey='Expediente';
    protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    /** Expediente administrativo asociado a la justificación. */
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    /** Municipio de la obra, con valor por defecto si no existe. */
    public function municipios(){
        return $this->belongsTo(TablaDeMunicipio::class,'municipio','codigo_municipio')
        ->withDefault([
            'nombre_municipio' => 'Sin municipio', // Valor por defecto
        ]);
    }
    /** Carretera provincial asociada cuando la obra es viaria. */
    public function carrteras():BelongsTo
    {
        return $this->belongsTo(TablaDeCarretera::class, 'carretera', 'Cod_Car' );
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
