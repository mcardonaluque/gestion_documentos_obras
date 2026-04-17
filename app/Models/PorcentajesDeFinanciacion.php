<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de parámetros de financiación por plan, año y organismo.
 *
 * Su finalidad es almacenar fórmulas y porcentajes reutilizables para cálculos
 * de cofinanciación entre administraciones u organismos participantes.
 */
class PorcentajesDeFinanciacion extends Model
{
    protected $connection = 'Obras';
    protected $table = 'dbo.Porcentajes_de_Financiacion';
    protected $primaryKey = 'Codigo_Plan';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];
}
