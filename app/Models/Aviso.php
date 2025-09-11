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
    protected $primaryKey='Id';
    protected $foreignKey = 'TipoAviso';
    //protected $keyType = 'string';

    protected $fillable = [
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
    public function tipoaviso(){
        return $this->belongsTo(TiposAviso::class);
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
   
}
