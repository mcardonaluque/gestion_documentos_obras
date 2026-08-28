<?php

declare(strict_types=1);

namespace App\Enums;

enum EstadoContratacionObra: string
{
    case Contratada = 'contratada';
    case Pendiente = 'pendiente';

    public function label(): string
    {
        return match ($this) {
            self::Contratada => 'Obras contratadas',
            self::Pendiente => 'Pendientes de contratación',
        };
    }
}
