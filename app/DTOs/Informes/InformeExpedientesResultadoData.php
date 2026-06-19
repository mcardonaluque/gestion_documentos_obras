<?php

declare(strict_types=1);

namespace App\DTOs\Informes;

use DateTimeImmutable;

final class InformeExpedientesResultadoData
{
    /**
     * @param array<int, InformeExpedientesGrupoData> $grupos
     */
    public function __construct(
        public readonly InformeExpedientesFiltrosData $filtros,
        public readonly array $grupos,
        public readonly int $totalExpedientes,
        public readonly float $totalImporteAprobado,
        public readonly DateTimeImmutable $generadoEn,
    ) {
    }

    public function tieneResultados(): bool
    {
        return $this->totalExpedientes > 0;
    }
}
