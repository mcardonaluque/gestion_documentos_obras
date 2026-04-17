<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabla parametrizada de porcentajes económicos aplicables a proyectos por año.
 *
 * Sirve como fuente de valores por defecto para gastos generales, beneficio
 * industrial, IVA, subcontrata y honorarios cuando se crea o recalcula un proyecto.
 */
class PorcentajesProyectos extends Model
{
    protected $connection = 'Obras';
    protected $table = 'dbo.PorcentajesProyectos';
    protected $primaryKey = 'AoProyecto';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    /**
     * Devuelve los porcentajes por defecto aplicables al año de proyecto indicado.
     *
     * @return array<string, float|null>
     */
    public static function defaultsForAoProyecto(null|int|string $aoProyecto): array
    {
        $query = self::query();

        $row = null;
        if ($aoProyecto !== null && $aoProyecto !== '') {
            $row = $query->where('AoProyecto', (int) $aoProyecto)->first();
        }

        if (! $row) {
            $row = self::query()->orderByDesc('AoProyecto')->first();
        }

        if (! $row) {
            return [];
        }

        return [
            'por_gastos_generales' => self::toFloat($row->GG),
            'por_beneficio_industriales' => self::toFloat($row->BI),
            'por_control_calidad' => self::toFloat($row->CC),
            'por_iva' => self::toFloat($row->IV),
            'por_subcontrata' => self::toFloat($row->SU),
            'por_honorarios_dir' => self::toFloat($row->HD),
            'por_honorarios_red' => self::toFloat($row->HR),
            'por_plan_sys' => self::toFloat($row->BT),
        ];
    }

    private static function toFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        return round((float) $value, 2);
    }
}
