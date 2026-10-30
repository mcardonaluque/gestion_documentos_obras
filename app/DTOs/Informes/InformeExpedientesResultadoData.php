<?php

declare(strict_types=1);

namespace App\DTOs\Informes;

use DateTimeImmutable;

/**
 * DTO inmutable con el resultado completo del informe agrupado de expedientes.
 *
 * Reúne los filtros aplicados, los grupos generados, los totales globales y la
 * fecha de generación. Producido por InformeExpedientesService y consumido por
 * la página Filament InformeExpedientesAgrupado y la vista de impresión.
 */
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
