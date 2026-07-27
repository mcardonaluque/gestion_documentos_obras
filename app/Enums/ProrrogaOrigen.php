<?php

declare(strict_types=1);

namespace App\Enums;

enum ProrrogaOrigen: string
{
    /** La prórroga nace de una solicitud formal. */
    case SOLICITUD = 'solicitud';
    /** La prórroga se inicia de oficio. */
    case OFICIO = 'oficio';

    /**
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
