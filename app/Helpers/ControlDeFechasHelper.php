<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Helper de validación de fechas basado en la tabla reglas_validacion_fechas.
 *
 * Carga las reglas activas aplicables a la tabla del modelo indicado y lanza
 * ValidationException si una fecha incumple su relación (after/before/
 * after_or_equal) con el campo relacionado. Sin consumidores directos
 * registrados actualmente en app/ (reemplazado por los servicios DateRules).
 */
class ControlDeFechasHelper
{
    /**
     * Valida los campos de fecha de $data contra las reglas activas de la tabla del modelo.
     *
     * @param  class-string<Model>  $modelClass
     * @param  array<string, mixed>  $data
     * @throws ValidationException Si alguna regla de fechas no se cumple.
     */
    public function validateDateRules(string $modelClass, array $data, ?int $recordId = null): void
    {
        $table = (new $modelClass())->getTable();

        $rules = DB::table('reglas_validacion_fechas')
            ->where('activa', true)
            ->where(function ($query) use ($table) {
                $query->where('field_to_validate', 'like', $table . '.%')
                      ->orWhere('related_table', $table);
            })
            ->get();

        foreach ($rules as $rule) {
            $this->applyRule($rule, $data, $recordId);
        }
    }

    /**
     * Aplica una regla concreta al dato recibido según su validation_type.
     */
    protected function applyRule($rule, $data, $recordId = null)
    {
        // Extraer el nombre del campo sin el nombre de la tabla
        $fieldName = last(explode('.', $rule->field_to_validate));

        if (!array_key_exists($fieldName, $data)) {
            return;
        }

        // Obtener el valor del campo relacionado
        $relatedValue = $this->getRelatedValue($rule, $data, $recordId);

        if (!$relatedValue) {
            return;
        }

        // Aplicar la validación según el tipo
        switch ($rule->validation_type) {
            case 'after':
                if (strtotime($data[$fieldName]) <= strtotime($relatedValue)) {
                    throw ValidationException::withMessages([
                        $fieldName => "La fecha debe ser posterior a {$relatedValue}"
                    ]);
                }
                break;

            case 'before':
                if (strtotime($data[$fieldName]) >= strtotime($relatedValue)) {
                    throw ValidationException::withMessages([
                        $fieldName => "La fecha debe ser anterior a {$relatedValue}"
                    ]);
                }
                break;

            case 'after_or_equal':
                if (strtotime($data[$fieldName]) < strtotime($relatedValue)) {
                    throw ValidationException::withMessages([
                        $fieldName => "La fecha debe ser igual o posterior a {$relatedValue}"
                    ]);
                }
                break;

            // Más tipos de validación según necesites
        }
    }

    /**
     * Obtiene el valor del campo relacionado contra el que se compara la fecha.
     */
    protected function getRelatedValue($rule, $data, $recordId = null)
    {
        // Si la relación está en el mismo modelo
        if ($rule->related_table === $rule->field_to_validate) {
            if ($recordId) {
                return DB::table($rule->related_table)
                    ->where('id', $recordId)
                    ->value($rule->related_field);
            }
            return null;
        }

        // Si hay una relación definida en los datos (para creates)
        $relationField = str_replace($rule->related_table.'_', '', $rule->related_field);
        if (array_key_exists($relationField, $data)) {
            return $data[$relationField];
        }

        // Para updates, obtener de la relación
        if ($recordId) {
            $relatedId = DB::table($rule->field_to_validate)
                ->where('id', $recordId)
                ->value(str_replace($rule->related_table.'_', '', $rule->related_field));

            if ($relatedId) {
                return DB::table($rule->related_table)
                    ->where('id', $relatedId)
                    ->value($rule->related_field);
            }
        }

        return null;
    }
}
