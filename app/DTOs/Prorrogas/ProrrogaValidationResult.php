<?php

declare(strict_types=1);

namespace App\DTOs\Prorrogas;

/**
 * Resultado de validación de reglas de prórroga.
 *
 * @property-read bool $valid Indica si la solicitud cumple las reglas.
 * @property-read string|null $message Mensaje de validación o explicación del rechazo.
 * @property-read int $maxAllowedDays Límite máximo permitido para la prórroga.
 */
final readonly class ProrrogaValidationResult
{
    /**
     * Crea un resultado de validación inmutable.
     */
    public function __construct(
        public bool $valid,
        public ?string $message,
        public int $maxAllowedDays,
    ) {
    }
}
