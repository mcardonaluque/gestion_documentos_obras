<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Operación del ciclo de vida del modelo (alta, actualización o ambas)
 * sobre la que aplica una regla de validación de fechas.
 *
 * Lo usan `DateValidationRule`, `DateRuleObserver`, `DateRuleEvaluator`
 * y el recurso de Filament `DateValidationRuleResource` para decidir
 * cuándo evaluar cada regla.
 */
enum DateRuleOperation: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case BOTH = 'both';

    /**
     * Opciones legibles (valor => etiqueta) para selects de Filament.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::CREATE->value => 'Alta',
            self::UPDATE->value => 'Actualizacion',
            self::BOTH->value => 'Alta y actualizacion',
        ];
    }

    /**
     * Deriva la operación a partir del estado del modelo:
     * UPDATE si ya existe en base de datos, CREATE en caso contrario.
     */
    public static function fromModelState(bool $exists): self
    {
        return $exists ? self::UPDATE : self::CREATE;
    }
}
