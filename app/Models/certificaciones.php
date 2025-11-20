<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function Obra():BelongsTo
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id','expediente_id' );
    }
    public function datosejecucion():BelongsTo
    {
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id','expediente_id' );
    }
    public function importes():HasOne    {
        return $this->hasOne(ImportesDeObras::class, 'expediente_id','expediente_id');
    }
    public function importesPorOrganismo():HasMany
    {
        return $this->hasMany(ImportesporOrganismo::class, 'expediente_id', 'expediente_id' );
    }
    public function tipoJustificante():BelongsTo
    {
        return $this->belongsTo(tiposjustificante::class, 'tipo_justificante', 'id' );
    }
    public function estado():BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class, 'estado_certif', 'cod_estado' );
    }
    public function planes():BelongsTo
    {
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan' );
    }
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
