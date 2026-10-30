<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Severidad del resultado de una regla de validación de fechas:
 * advertencia, error bloqueante o simple notificación.
 *
 * Lo usan `DateValidationRule`, el DTO `DateRuleEvaluationResult`,
 * `DateRuleNotifier` y el recurso de Filament `DateValidationRuleResource`.
 */
enum DateRuleType: string
{
    case WARNING = 'warning';
    case ERROR = 'error';
    case NOTIFICATION = 'notification';

    /**
     * Opciones legibles (valor => etiqueta) para selects de Filament.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::WARNING->value => 'Advertencia',
            self::ERROR->value => 'Error',
            self::NOTIFICATION->value => 'Notificacion',
        ];
    }

    /**
     * Indica si la regla debe impedir guardar el registro (solo ERROR).
     */
    public function isBlocking(): bool
    {
        return $this === self::ERROR;
    }
}
