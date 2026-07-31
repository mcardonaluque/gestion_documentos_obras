<?php

declare(strict_types=1);

namespace App\Enums;

enum ProrrogaTipo: string

    /** Prórroga relativa a la presentación de documentación. */

{
    /** Prórroga vinculada a la normativa general del plan. */
    case NORMATIVA = 'normativa';
    /** Prórroga relativa al plazo de ejecución de la obra. */
    case EJECUCION = 'ejecucion';
    /** Prórroga relativa al plazo de justificación. */
    case JUSTIFICACION = 'justificacion';
    /** Prórroga relativa a la presentación del proyecto. */
    case PROYECTO = 'proyecto';
    /** Prórroga relativa a la presentación del proyecto y documentación. */
    case PROYECTODC = 'proyectodc';

    case DOCUMENTACION = 'documentacion';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::NORMATIVA->value => 'Prórroga normativa',
            self::EJECUCION->value => 'Prórroga de ejecución',
            self::JUSTIFICACION->value => 'Prórroga de justificación',
            self::PROYECTO->value => 'Prórroga de presentación de proyecto',
            self::PROYECTODC->value => 'Prórroga de presentación de proyecto y documentación',
            self::DOCUMENTACION->value => 'Prórroga de presentación de documentación'

        ];
    }
}
