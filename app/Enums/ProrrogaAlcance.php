<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Ámbito de una prórroga: proyecto/memoria, documentación o ambos.
 *
 * Lo usan los relation managers `SolicitudesProrrogaRelationManager`
 * (panel Ayuntamientos) y `ProrrogasRelationManager` (panel Obras) en
 * los formularios de solicitud y registro de prórrogas.
 */
enum ProrrogaAlcance: string
{
    /** Afecta al proyecto o memoria técnica. */
    case PROYECTO_MEMORIA = 'proyecto_memoria';
    /** Afecta a la documentación aportada. */
    case DOCUMENTACION = 'documentacion';
    /** Afecta a ambos ámbitos. */
    case AMBAS = 'ambas';

    /**
     * Opciones legibles (valor => etiqueta) para selects de Filament.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::PROYECTO_MEMORIA->value => 'Presentación de proyecto/memoria',
            self::DOCUMENTACION->value => 'Presentación de documentación',
            self::AMBAS->value => 'Proyecto/memoria y documentación',
        ];
    }
}
