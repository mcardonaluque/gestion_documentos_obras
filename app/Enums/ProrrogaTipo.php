<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Tipo de prórroga según el plazo al que afecta (normativa, ejecución,
 * justificación, proyecto, proyecto y documentación, o documentación).
 *
 * Lo usan `RegistrarProrrogaAction` y los relation managers
 * `SolicitudesProrrogaRelationManager` (panel Ayuntamientos) y
 * `ProrrogasRelationManager` (panel Obras).
 */
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

    /** Prórroga relativa a la presentación de documentación. */
    case DOCUMENTACION = 'documentacion';

    /**
     * Tipos que se tramitan en la fase de aprobación de la obra (plazos de
     * normativa, proyecto y documentación), es decir, todos salvo ejecución
     * y justificación.
     *
     * @return list<self>
     */
    public static function aprobacion(): array
    {
        return [
            self::NORMATIVA,
            self::PROYECTO,
            self::PROYECTODC,
            self::DOCUMENTACION,
        ];
    }

    /**
     * Valores de una lista de tipos, para filtros de consulta.
     *
     * @param list<self> $tipos
     * @return list<string>
     */
    public static function valores(array $tipos): array
    {
        return array_map(static fn (self $tipo): string => $tipo->value, $tipos);
    }

    /**
     * Valores de {@see self::aprobacion()} para filtros de consulta.
     *
     * @return list<string>
     */
    public static function valoresAprobacion(): array
    {
        return array_map(static fn (self $tipo): string => $tipo->value, self::aprobacion());
    }

    /**
     * Opciones legibles (valor => etiqueta) para selects de Filament.
     *
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
