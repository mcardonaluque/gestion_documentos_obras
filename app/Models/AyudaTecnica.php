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
    protected $primaryKey='Expediente';
    protected $foreignKey = 'departamento';
    public $incrementing=false;
    protected $keyType='string';
    protected $fillable = [
        'Expediente',
        'dpto_redactor',
        'departamento_direccion',
        'AyuTecRed',
        'AyuTecDir',
        'SubvencionEconomicaR',
        'SubvencionEconomicaD',
        'codigo_municipio',
    ];
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
        return $this->belongsTo(DatosDeInicioDeObras::class, 'Expediente', 'Expediente' );
    }
    public function municipio():BelongsTo
    {
        return $this->belongsTo(TablaDeMunicipio::class, 'codigo_municipio', 'codigo_municipio' );
    }
}
