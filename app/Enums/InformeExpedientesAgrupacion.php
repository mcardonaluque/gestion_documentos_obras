<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Criterio de agrupación del informe de expedientes: por estado,
 * municipio o año de ejecución.
 *
 * Lo usan `ImprimirInformeExpedientesRequest`, el DTO
 * `InformeExpedientesFiltrosData`, `InformeExpedientesService` y la
 * página de Filament `InformeExpedientesAgrupado` del panel Obras.
 */
enum InformeExpedientesAgrupacion: string
{
    case ESTADO = 'estado';
    case MUNICIPIO = 'municipio';
    case ANIO = 'anio';

    /**
     * Opciones legibles (valor => etiqueta) para el selector de agrupación.
     *
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
