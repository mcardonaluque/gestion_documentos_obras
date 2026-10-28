<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit\Framework\Constraint\IsTrue;

class Aviso extends Model
{
    //
    protected $connection='Obras';
    public $incrementing = true;
    protected $table='Avisos';
    protected $dateFormat = 'Ymd H:i:s';
    protected $primaryKey='id';
   //protected $foreignKey = 'TipoAviso';
    //protected $keyType = 'string';

    protected $fillable = [
        'codigo_hito',
        'fecha_hito',
        'expediente_id',
        'team_id',
        'Referencia',
        'TipoAviso',
        'Codigo_Plan',
        'numero_obra',
        'subreferencia',
        'ao_ejecucion',
        'Usuario',
        'FecSolucion',
        'borrado',
    ];
    protected $casts = [
        'borrado' => 'boolean',
        'FecSolucion' => 'datetime',
    ];
    public function tipodeaviso() : BelongsTo{
        return $this->belongsTo(TiposAviso::class, 'TipoAviso', 'TipoAviso');
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
   
}
