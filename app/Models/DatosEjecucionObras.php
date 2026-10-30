<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Datos de la fase de ejecución de una obra.
 *
 * Centraliza la información del seguimiento de ejecución: actas de replanteo
 * y recepción, prórrogas, certificaciones e importes. Tabla
 * 'Datos_Ejecucion_Obras' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'expediente_id' y sin timestamps.
 */
class DatosEjecucionObras extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='Datos_Ejecucion_Obras';
    protected $primaryKey='expediente_id';

    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    /** Expediente administrativo asociado a la ejecución. */
    public function expediente(){
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id');
    }
    /** Importes globales de la obra en ejecución. */
    function importes():HasOne   {
        return $this->hasOne(ImportesDeObras::class, 'expediente_id', 'expediente_id');
    }
    /** Distribución de importes por organismo financiador. */
    public function importesPorOrganismo():HasMany
    {
        return $this->hasMany(ImportesPorOrganismo::class, 'expediente_id', 'expediente_id' );
    }

    /** Plan provincial al que pertenece la obra. */
    public function planes():BelongsTo
    {
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan' );
    }
    /** Ficha de inicio de la obra en ejecución. */
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
    }
    /** Certificaciones económicas emitidas durante la ejecución. */
    public function certificaciones():HasMany
    {
        return $this->Hasmany(certificaciones::class, 'expediente_id', 'expediente_id' );
    }
    /** Plan de seguridad y salud de la obra. */
    function pseguridadsalud():HasOne   {
        return $this->hasOne(Planseguridadysalud::class, 'expediente_id', 'expediente_id');
    }

    /** Acta de comprobación de replanteo de la obra. */
    public function actaReplanteo(): HasOne
    {
        return $this->hasOne(actadereplanteo::class, 'expediente_id', 'expediente_id');
    }

    /** Acta de recepción de la obra. */
    public function actaRecepcion(): HasOne
    {
        return $this->hasOne(actaderecepcion::class, 'expediente_id', 'expediente_id');
    }

    /** Prórrogas de plazo concedidas a la obra. */
    public function prorrogas(): HasMany
    {
        return $this->hasMany(\App\Models\Prorroga::class, 'expediente_id', 'expediente_id');
    }
}
