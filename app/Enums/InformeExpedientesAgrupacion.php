<?php

declare(strict_types=1);

namespace App\Enums;

enum InformeExpedientesAgrupacion: string
{
    case ESTADO = 'estado';
    case MUNICIPIO = 'municipio';
    case ANIO = 'anio';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::ESTADO->value => 'Estado',
            self::MUNICIPIO->value => 'Municipio',
            self::ANIO->value => 'Año de ejecución',
        ];
    }
}
