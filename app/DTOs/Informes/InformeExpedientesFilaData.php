<?php

declare(strict_types=1);

namespace App\DTOs\Informes;

/**
 * DTO inmutable de una fila del informe agrupado de expedientes.
 *
 * Contiene los datos mostrados por expediente (obra, estado, municipio, año,
 * importe aprobado) y su clave/etiqueta de grupo. Lo crea
 * InformeExpedientesService y se agrupa en InformeExpedientesGrupoData.
 */
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
