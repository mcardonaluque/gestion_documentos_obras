<?php

declare(strict_types=1);

namespace App\Services\DateRules;

use Illuminate\Database\Eloquent\Model;

/**
 * Determina qué atributos de un modelo son fechas sujetas a las reglas de date_validation_rules.
 *
 * Un atributo es fecha si tiene un cast de fecha (date, datetime, immutable_*,
 * date:formato o datetime:formato). Para las columnas heredadas sin cast se
 * mantiene como respaldo la detección por nombre (contiene "fecha"). Las
 * columnas de timestamps de Laravel (created_at/updated_at) nunca se evalúan.
 * Lo usan DateRuleObserver y el trait NotifiesAndValidatesDates.
 */
final class DateFieldDetector
{
    /** @var array<int, string> */
    private const DATE_CAST_TYPES = [
        'date',
        'datetime',
        'immutable_date',
        'immutable_datetime',
        'custom_datetime',
        'immutable_custom_datetime',
        'timestamp',
    ];

    /**
     * Devuelve los atributos modificados del modelo que son fechas.
     *
     * @return array<int, string>
     */
    public function dirtyDateFields(Model $model): array
    {
        /** @var array<int, string> $dirtyFields */
        $dirtyFields = array_keys($model->getDirty());

        return array_values(array_filter(
            $dirtyFields,
            fn (string $field): bool => $this->isDateField($model, $field),
        ));
    }

    /**
     * Indica si un atributo del modelo debe tratarse como fecha.
     */
    public function isDateField(Model $model, string $field): bool
    {
        if (in_array($field, $this->timestampColumns($model), true)) {
            return false;
        }

        if ($model->hasCast($field, self::DATE_CAST_TYPES)) {
            return true;
        }

        return str_contains(strtolower($field), 'fecha');
    }

    /**
     * @return array<int, string>
     */
    private function timestampColumns(Model $model): array
    {
        if (! $model->usesTimestamps()) {
            return [];
        }

        return array_values(array_filter([
            $model->getCreatedAtColumn(),
            $model->getUpdatedAtColumn(),
        ]));
    }
}
