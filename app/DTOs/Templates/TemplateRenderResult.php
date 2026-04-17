<?php

declare(strict_types=1);

namespace App\DTOs\Templates;

/**
 * DTO con el resultado de la renderización de una plantilla documental.
 *
 * Transporta la ruta temporal generada, el nombre sugerido para descarga,
 * el formato final de salida y la lista de variables utilizadas.
 */
final readonly class TemplateRenderResult
{
    /**
     * @param list<string> $variables
     */
    public function __construct(
        public string $outputPath,
        public string $downloadName,
        public string $format,
        public array $variables = [],
    ) {
    }
}
