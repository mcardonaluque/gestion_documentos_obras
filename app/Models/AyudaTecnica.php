<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Departamentos;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AyudaTecnica extends Model
{
    //
    protected $connection='Obras';
    protected $table='Ayuda_Tecnica';
    protected $primaryKey='expediente_id';
    protected $foreignKey = 'departamento';
    public $incrementing=false;
    protected $keyType='string';
    protected $fillable = [
        'expediente_id',
        'dpto_redactor',
        'departamento_direccion',
        'AyuTecRed',
        'AyuTecDir',
        'SubvencionEconomicaR',
        'SubvencionEconomicaD',
        'codigo_municipio',
    ];
    protected static function booted()
    {
        static::creating(function ($ayuda) {
            if ($ayuda->obra) {
                $ayuda->Codigo_Plan     = $ayuda->obra->Codigo_Plan;
                $ayuda->numero_obra     = $ayuda->obra->numero_obra;
                $ayuda->subreferencia   = $ayuda->obra->subreferencia;
                $ayuda->ao_ejecucion    = $ayuda->obra->ao_ejecucion;
            }
        });
    }
    public function ayudaR():BelongsTo
    {
        return $this->belongsTo(Departamentos::class, 'dpto_redactor', 'CODIGO_DPTO' );
    }
    public function ayudaD():BelongsTo
    {
        return $this->belongsTo(Departamentos::class, 'departamento_direccion', 'CODIGO_DPTO' );
    }
    public function Obra():BelongsTo
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id' );
    }
    public function municipio():BelongsTo
    {
        return $this->belongsTo(TablaDeMunicipio::class, 'codigo_municipio', 'codigo_municipio' );
    }
}
