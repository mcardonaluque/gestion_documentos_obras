<?php

declare(strict_types=1);

namespace App\Enums;

enum ProrrogaTipo: string
{
    /** Prórroga vinculada a la normativa general del plan. */
    case NORMATIVA = 'normativa';
    /** Prórroga relativa al plazo de ejecución de la obra. */
    case EJECUCION = 'ejecucion';
    /** Prórroga relativa al plazo de justificación. */
    case JUSTIFICACION = 'justificacion';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::NORMATIVA->value => 'Prórroga normativa',
            self::EJECUCION->value => 'Prórroga de ejecución',
            self::JUSTIFICACION->value => 'Prórroga de justificación',
        ];
    }
}
