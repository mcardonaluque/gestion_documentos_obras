<?php

declare(strict_types=1);

namespace App\DTOs\Informes;

final class InformeExpedientesFilaData
{
    public function __construct(
        public readonly string $claveGrupo,
        public readonly string $etiquetaGrupo,
        public readonly string $expedienteId,
        public readonly string $nombreObra,
        public readonly string $estado,
        public readonly string $municipio,
        public readonly int $anioEjecucion,
        public readonly float $importeAprobado,
    ) {
    }
}
