<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Origen de una prórroga: solicitada por el interesado o iniciada de oficio.
 *
 * Lo usan los relation managers `SolicitudesProrrogaRelationManager`
 * (panel Ayuntamientos) y `ProrrogasRelationManager` (panel Obras).
 */
enum ProrrogaOrigen: string
{
    /** La prórroga nace de una solicitud formal. */
    case SOLICITUD = 'solicitud';
    /** La prórroga se inicia de oficio. */
    case OFICIO = 'oficio';

    /**
     * Opciones legibles (valor => etiqueta) para selects de Filament.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::SOLICITUD->value => 'A solicitud',
            self::OFICIO->value => 'De oficio',
        ];
    }
}
