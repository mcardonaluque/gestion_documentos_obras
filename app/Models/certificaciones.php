<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Certificación económica mensual/parcial de una obra.
 *
 * Representa las certificaciones de obra presentadas para su abono, con su
 * estado de tramitación y tipo de justificante. Tabla 'CertificacionesDeObras'
 * en la conexión 'Obras' (SQL Server), clave primaria string 'expediente_id'
 * y sin timestamps. Se relaciona con la obra, su ejecución, importes y plan.
 */
class certificaciones extends Model
{
    //
    protected $connection='Obras';
    protected $table='CertificacionesDeObras';
    protected $primaryKey='expediente_id';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
    /** Ficha de inicio de obra certificada. */
    public function Obra():BelongsTo
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id','expediente_id' );
    }
    /** Datos de ejecución de la obra certificada. */
    public function datosejecucion():BelongsTo
    {
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id','expediente_id' );
    }
    /** Importes globales de la obra certificada. */
    public function importes():HasOne    {
        return $this->hasOne(ImportesDeObras::class, 'expediente_id','expediente_id');
    }
    /** Distribución de importes por organismo financiador. */
    public function importesPorOrganismo():HasMany
    {
        return $this->hasMany(ImportesporOrganismo::class, 'expediente_id', 'expediente_id' );
    }
    /** Tipo de justificante usado en la certificación. */
    public function tipoJustificante():BelongsTo
    {
        return $this->belongsTo(tiposjustificante::class, 'tipo_justificante', 'id' );
    }
    /** Estado de tramitación de la certificación. */
    public function estado():BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class, 'estado_certif', 'cod_estado' );
    }
    /** Plan provincial al que pertenece la obra certificada. */
    public function planes():BelongsTo
    {
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan' );
    }
    /** Team (ayuntamiento/ámbito) propietario de la certificación. */
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
