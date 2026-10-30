<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Estado de contratación de una obra: ya contratada o pendiente.
 *
 * Lo usan `PendienteContratacionObraResource` (panel Obras) y
 * `PendienteContratacionObraQueryService` para filtrar los listados
 * de obras según su situación de contratación.
 */
enum EstadoContratacionObra: string
{
    case Contratada = 'contratada';
    case Pendiente = 'pendiente';

    /**
     * Etiqueta legible del estado para listados y filtros.
     */
    public function label(): string
    {
        return match ($this) {
            self::Contratada => 'Obras contratadas',
            self::Pendiente => 'Pendientes de contratación',
        };
    }
}
