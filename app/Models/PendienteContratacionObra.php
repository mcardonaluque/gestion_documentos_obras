<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendienteContratacionObra extends Model
{
    //
    protected $connection='Obras';
    protected $table='PendienteContratacionObras';
    protected $primaryKey='exoediente_id';
    protected $keyType = 'string';
    protected $guarded = [];

    protected $casts = [
        'FechaAdjudicacion' => 'datetime',
        'FechaContrato' => 'datetime',
        'ImporteAdjudicacion' => 'decimal:2',
        'ImporteAdjudicacion_Pts' => 'decimal:2',
    ];

    public $incrementing=false;
    public $timestamps=false;
    public function contratista(): BelongsTo
    {
        return $this->belongsTo (Contratista::class,'Codcontratista','Codigo_contratista');
    }
}
