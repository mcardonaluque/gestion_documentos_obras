<?php

declare(strict_types=1);

namespace App\DTOs\Informes;

/**
 * DTO inmutable de un grupo de filas del informe de expedientes.
 *
 * Agrupa filas InformeExpedientesFilaData bajo una clave/etiqueta (estado,
 * municipio o año) y calcula sus totales. Creado por InformeExpedientesService
 * y mostrado en la página Filament InformeExpedientesAgrupado.
 */
final class InformeExpedientesGrupoData
{
    /**
     * @param array<int, InformeExpedientesFilaData> $filas
     */
    public function __construct(
        public readonly string $clave,
        public readonly string $etiqueta,
        public readonly array $filas,
    ) {
    }

    public function totalExpedientes(): int
    {
        return count($this->filas);
    }

    /**
     * Suma el importe aprobado de todas las filas del grupo.
     */
    public function totalImporteAprobado(): float
    {
        $total = 0.0;

        foreach ($this->filas as $fila) {
            $total += $fila->importeAprobado;
        }

        return $total;
    }
}
