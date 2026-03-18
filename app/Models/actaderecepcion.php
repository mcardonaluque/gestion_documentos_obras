<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class actaderecepcion extends Model
{
    protected $connection = 'Obras';
    protected $table = 'ActasRecepcionObra';
    protected $primaryKey = 'expediente_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'expediente_id',
        'TipoActaRecepcion',
        'Fecha_Acta_RecProv',
        'Lugar_Acta_Rec',
        'Fecha_Com_Inf',
        'Fecha_Edicto_BOE',
        'Fecha_BOE',
        'Num_BOE',
        'Plazo_Reclam',
        'Fecha_Certif_NO_Reclam',
        'Fecha_Com_Inf_2',
        'Fecha_Com_Gob',
        'Fecha_Comun_Contrat',
        'Fecha_Certif_Liquid',
        'Fecha_Rem_Interv',
        'Fecha_Rem_MAP',
        'Admin_ActaRecepcion',
        'Dir_ActaRecepcion',
        'Alcalde_ActaRecepcion',
        'Cont_ActaRecepcion',
        'Interv_ActaRecepcion',
        'Dipu_ActaRecepcion',
        'Texto',
        'Fecha_Paralizacion_Temporal',
        'Motivo_Paralizacion',
        'Fecha_Aprob_Paralizacion_Temporal',
        'Fecha_Inicio_Paralizacion',
        'Fecha_Final_Paralizacion',
        'Fecha_Acta_Rec',
        'Fecha_Aviso_Finalizacion',
        'Fecha_Aviso_FinalizacionMAP',
        'Fecha_Medicion',
        'team_id',
    ];

    protected $casts = [
        'Fecha_Acta_RecProv' => 'datetime',
        'Fecha_Com_Inf' => 'datetime',
        'Fecha_Edicto_BOE' => 'datetime',
        'Fecha_BOE' => 'datetime',
        'Fecha_Certif_NO_Reclam' => 'datetime',
        'Fecha_Com_Inf_2' => 'datetime',
        'Fecha_Com_Gob' => 'datetime',
        'Fecha_Comun_Contrat' => 'datetime',
        'Fecha_Certif_Liquid' => 'datetime',
        'Fecha_Rem_Interv' => 'datetime',
        'Fecha_Rem_MAP' => 'datetime',
        'Fecha_Paralizacion_Temporal' => 'datetime',
        'Fecha_Aprob_Paralizacion_Temporal' => 'datetime',
        'Fecha_Inicio_Paralizacion' => 'datetime',
        'Fecha_Final_Paralizacion' => 'datetime',
        'Fecha_Acta_Rec' => 'datetime',
        'Fecha_Aviso_Finalizacion' => 'datetime',
        'Fecha_Aviso_FinalizacionMAP' => 'datetime',
        'Fecha_Medicion' => 'datetime',
    ];

    public function ejecucion(): BelongsTo
    {
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');
    }
}
