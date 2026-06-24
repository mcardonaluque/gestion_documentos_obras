<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prorroga extends Model
{
    protected $connection = 'Obras';

    protected $table = 'Prorrogas';

    protected $primaryKey = 'NumSec';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'PlanObra',
        'NumObra',
        'SubRef',
        'AoObra',
        'NumSec',
        'FecPeticionProrrogaDeDip',
        'FecSolicitudProrrogaDeCont',
        'FecProrroga',
        'FecSolicitudInfTec',
        'FecInfTec',
        'FecComInf',
        'FecComGob',
        'FecDecreto',
        'NumDecreto',
        'MotivoProrroga',
        'expediente_id',
        'team_id',
    ];

    protected $casts = [
        'NumObra' => 'integer',
        'SubRef' => 'integer',
        'AoObra' => 'integer',
        'NumSec' => 'integer',
        'FecPeticionProrrogaDeDip' => 'datetime',
        'FecSolicitudProrrogaDeCont' => 'datetime',
        'FecProrroga' => 'datetime',
        'FecSolicitudInfTec' => 'datetime',
        'FecInfTec' => 'datetime',
        'FecComInf' => 'datetime',
        'FecComGob' => 'datetime',
        'FecDecreto' => 'datetime',
    ];

    public function ejecucion(): BelongsTo
    {
        return $this->belongsTo(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');
    }
}
