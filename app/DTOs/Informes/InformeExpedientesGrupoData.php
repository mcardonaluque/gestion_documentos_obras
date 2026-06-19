<?php

declare(strict_types=1);

namespace App\DTOs\Informes;

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

    public function totalImporteAprobado(): float
    {
        $total = 0.0;

        foreach ($this->filas as $fila) {
            $total += $fila->importeAprobado;
        }

        return $total;
    }
}
